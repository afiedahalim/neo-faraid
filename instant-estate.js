// instant-estate.js - Complete rewrite for the new flow

class InstantEstateManager {
    constructor() {
        this.sessionId = null;
        this.extractedData = null;
        this.uploadedFile = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.checkExistingSession();
    }

    bindEvents() {
        // File upload handling
        $('#upload-form').on('submit', (e) => this.handleUpload(e));
        
        // Edit button click
        $(document).on('click', '#edit-continue-btn', (e) => this.handleEditContinue(e));
        
        // CAPTCHA verification
        $(document).on('click', '#verify-captcha-btn', (e) => this.handleCaptchaVerification(e));
        
        // Notification request
        $(document).on('click', '#request-notification-btn', (e) => this.handleNotificationRequest(e));
    }

    async checkExistingSession() {
        const sessionId = this.getSessionIdFromUrl();
        if (sessionId) {
            this.sessionId = sessionId;
            await this.loadSessionData();
        }
    }

    getSessionIdFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('session_id');
    }

    async loadSessionData() {
        try {
            const response = await fetch(`/instant-estate/extracted-data/${this.sessionId}`);
            const result = await response.json();
            
            if (result.success) {
                this.extractedData = result.extracted_data;
                this.displayExtractedData();
                this.updateStatus(result.status);
            }
        } catch (error) {
            console.error('Failed to load session data:', error);
        }
    }

    displayExtractedData() {
        if (!this.extractedData) return;

        // Display extracted data in the review panel
        const fields = [
            { key: 'deceased_name', label: 'Deceased Name' },
            { key: 'deceased_nric', label: 'NRIC/Passport' },
            { key: 'gender', label: 'Gender' },
            { key: 'date_of_birth', label: 'Date of Birth' },
            { key: 'death_date', label: 'Date of Death' },
            { key: 'death_place', label: 'Place of Death' },
            { key: 'cause_of_death', label: 'Cause of Death' },
            { key: 'father_name', label: "Father's Name" },
            { key: 'mother_name', label: "Mother's Name" },
            { key: 'spouse_name', label: "Spouse's Name" },
            { key: 'contact_email', label: 'Contact Email' },
            { key: 'contact_phone', label: 'Contact Phone' },
            { key: 'residential_address', label: 'Residential Address' },
        ];

        let html = '<div class="extracted-data-grid">';
        for (const field of fields) {
            const value = this.extractedData[field.key] || '<span class="text-muted">Not detected</span>';
            html += `
                <div class="data-row">
                    <div class="data-label">${field.label}:</div>
                    <div class="data-value">${this.escapeHtml(value)}</div>
                </div>
            `;
        }
        html += '</div>';

        $('#extracted-data-panel').html(html);
    }

    async handleUpload(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        $('#upload-status').html('<div class="spinner-border spinner-border-sm"></div> Uploading...');
        
        try {
            const response = await fetch('/instant-estate/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.sessionId = result.session_id;
                $('#upload-status').html('<span class="text-success">✓ Upload successful! Processing OCR...</span>');
                await this.processOCR();
            } else {
                $('#upload-status').html(`<span class="text-danger">✗ ${result.message}</span>`);
            }
        } catch (error) {
            console.error('Upload error:', error);
            $('#upload-status').html('<span class="text-danger">✗ Upload failed. Please try again.</span>');
        }
    }

    async processOCR() {
        if (!this.sessionId) return;
        
        $('#ocr-status').html('<div class="spinner-border spinner-border-sm"></div> Processing document...');
        
        try {
            const response = await fetch(`/instant-estate/process-ocr/${this.sessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.extractedData = result.extracted_data;
                this.displayExtractedData();
                
                const confidence = result.confidence;
                const missingCount = result.missing_fields?.length || 0;
                
                if (confidence >= 90 && missingCount === 0) {
                    $('#ocr-status').html('<span class="text-success">✓ OCR completed with high confidence!</span>');
                } else if (confidence >= 50) {
                    $('#ocr-status').html(`<span class="text-warning">⚠ OCR completed with ${confidence}% confidence. ${missingCount} field(s) need your input.</span>`);
                } else {
                    $('#ocr-status').html(`<span class="text-warning">⚠ OCR had difficulty. ${missingCount} field(s) need manual entry.</span>`);
                }
                
                // Show the edit button
                $('#edit-continue-btn').removeClass('d-none');
                $('#review-section').removeClass('d-none');
            } else {
                $('#ocr-status').html(`<span class="text-danger">✗ OCR failed: ${result.message}</span>`);
            }
        } catch (error) {
            console.error('OCR error:', error);
            $('#ocr-status').html('<span class="text-danger">✗ OCR processing failed. Please try again.</span>');
        }
    }

    async handleEditContinue(event) {
        const button = $(event.currentTarget);
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        
        try {
            // Collect any manual edits from the form
            const editedData = this.collectEditedData();
            
            const response = await fetch(`/instant-estate/edit-and-continue/${this.sessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ extracted_data: editedData })
            });
            
            const result = await response.json();
            
            if (result.success && result.redirect_url) {
                // Redirect to calculator with pre-filled data
                window.location.href = result.redirect_url;
            } else {
                button.prop('disabled', false).html('Edit & Continue');
                this.showToast('Error', result.message || 'Failed to save data', 'error');
            }
        } catch (error) {
            console.error('Edit continue error:', error);
            button.prop('disabled', false).html('Edit & Continue');
            this.showToast('Error', 'Failed to save data. Please try again.', 'error');
        }
    }

    collectEditedData() {
        // Collect values from editable fields
        const editedData = {};
        
        $('.editable-field').each((index, field) => {
            const key = $(field).data('field');
            const value = $(field).val();
            if (value && value.trim()) {
                editedData[key] = value.trim();
            }
        });
        
        return editedData;
    }

    async handleCaptchaVerification(event) {
        const button = $(event.currentTarget);
        const captchaToken = $('#captcha-token').val();
        
        if (!captchaToken) {
            this.showToast('Error', 'Please complete the CAPTCHA', 'error');
            return;
        }
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Verifying...');
        
        try {
            const response = await fetch(`/instant-estate/verify-captcha/${this.sessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ captcha_token: captchaToken })
            });
            
            const result = await response.json();
            
            if (result.success) {
                $('#captcha-status').html('<span class="text-success">✓ CAPTCHA verified successfully!</span>');
                $('#request-notification-btn').removeClass('d-none');
                this.showToast('Success', 'CAPTCHA verified! You can now request notification.', 'success');
            } else {
                $('#captcha-status').html(`<span class="text-danger">✗ ${result.message}</span>`);
                button.prop('disabled', false).html('Verify CAPTCHA');
            }
        } catch (error) {
            console.error('CAPTCHA error:', error);
            $('#captcha-status').html('<span class="text-danger">✗ Verification failed. Please try again.</span>');
            button.prop('disabled', false).html('Verify CAPTCHA');
        }
    }

    async handleNotificationRequest(event) {
        const button = $(event.currentTarget);
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Submitting...');
        
        try {
            const response = await fetch(`/instant-estate/request-notification/${this.sessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showToast('Success', result.message, 'success');
                $('#notification-status').html(`
                    <div class="alert alert-success">
                        <strong>✓ Request Submitted!</strong><br>
                        ${result.message}<br>
                        Request ID: ${result.request_id}
                    </div>
                `);
                button.remove();
            } else {
                this.showToast('Error', result.message, 'error');
                button.prop('disabled', false).html('Request Notification');
            }
        } catch (error) {
            console.error('Notification request error:', error);
            this.showToast('Error', 'Failed to submit notification request.', 'error');
            button.prop('disabled', false).html('Request Notification');
        }
    }

    updateStatus(status) {
        const statusMap = {
            'uploaded': 'Document uploaded',
            'processing_ocr': 'Processing document...',
            'ocr_completed': 'OCR completed',
            'data_confirmed': 'Data confirmed',
            'captcha_verified': 'CAPTCHA verified',
            'notification_requested': 'Notification requested',
            'completed': 'Completed',
            'failed': 'Failed'
        };
        
        $('#status-badge').text(statusMap[status] || status);
        
        // Update progress steps
        $('.step').removeClass('active completed');
        const steps = ['upload', 'ocr', 'review', 'captcha', 'notification'];
        const currentStep = this.getStepFromStatus(status);
        
        for (let i = 0; i <= currentStep; i++) {
            $(`.step-${steps[i]}`).addClass('active');
            if (i < currentStep) {
                $(`.step-${steps[i]}`).addClass('completed');
            }
        }
    }

    getStepFromStatus(status) {
        const stepMap = {
            'uploaded': 0,
            'processing_ocr': 1,
            'ocr_completed': 1,
            'data_confirmed': 2,
            'captcha_verified': 3,
            'notification_requested': 4,
            'completed': 4
        };
        return stepMap[status] || 0;
    }

    showToast(title, message, type = 'info') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        const container = $('#toast-container');
        const toast = $(toastHtml);
        container.append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        toast.on('hidden.bs.toast', () => toast.remove());
    }

    escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
}

// Initialize on page load
$(document).ready(() => {
    window.instantEstate = new InstantEstateManager();
});