<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="tab-container">
    <div class="tab-grid">
        <!-- Left Column -->
        <div class="tab-column">
            <div class="tab-card">
                <!-- QR Preview -->
                <div class="preview-section">
                    <div class="preview-header">
                        <h3>QR Preview</h3>
                        <span class="badge">Live Preview</span>
                    </div>
                    <div class="preview-area">
                        <img src="/api/placeholder/200/200" alt="QR Code" class="preview-image">
                    </div>
                </div>
                
                <!-- Logo Upload -->
                <div class="upload-section">
                    <h3>Business Logo</h3>
                    <div class="upload-area" id="logoDropzone">
                        <div class="upload-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p class="upload-text">Drop your logo here or click to upload <span class="upload-subtext">(Supported formats: PNG, JPG - Max 2MB)</span></p>
                        </div>
                    </div>
                </div>
                
                <!-- Color Customization -->
                <div class="color-section">
                    <h3>QR Customization</h3>
                    <div class="color-grid">
                        <div class="control-group">
                            <label class="color-label">QR Primary Color</label>
                            <div class="control-row">
                                <div class="color-input-group">
                                    <input type="color" id="primaryColor" value="#213165">
                                    <span class="color-hex">#213165</span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="color-label">QR Background Color</label>
                            <div class="control-row">
                                <div class="color-input-group">
                                    <input type="color" id="bgColor" value="#FFFFFF">
                                    <span class="color-hex">#FFFFFF</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="tab-column">
            <!-- Action Buttons -->
            <div class="action-grid">
                <button class="btn" id="downloadQR">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download QR
                </button>
                <button class="btn" id="copyLink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                    Copy Link
                    <span class="copy-feedback">Copied!</span>
                </button>
            </div>
            
            <!-- Email Form -->
            <div class="tab-card">
                <div class="form-header">
                    <h3 class="form-title">Email your card</h3>
                    <span class="counter-text">12/20 sent today</span>
                </div>
                
                <form class="tab-form" id="emailForm">
                    <div class="form-row">
                        <input type="text" id="name" placeholder="Name" required>
                        <input type="email" id="email" placeholder="Email" required>
                    </div>
                    <div class="form-row">
                        <textarea id="message" placeholder="Message (optional)" rows="3" maxlength="500"></textarea>
                        <div class="char-counter">0/500</div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn">
                            Send
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
