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
                <div class="preview-area">
                    <img src="/api/placeholder/200/200" alt="QR Code" class="preview-image">
                </div>
                
                <!-- Logo Upload -->
                <div class="upload-area">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <p class="text-sm text-gray">Drop your logo here or click to upload</p>
                </div>
                
                <!-- Color Pickers -->
                <div class="color-grid">
                    <div class="control-group">
                        <label>Color 1</label>
                        <div class="control-row">
                            <input type="color" value="#2563eb">
                            <div class="color-presets">
                                <button class="color-preset-btn" style="background-color: #2563eb"></button>
                                <button class="color-preset-btn" style="background-color: #3b82f6"></button>
                                <button class="color-preset-btn" style="background-color: #60a5fa"></button>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label>Color 2</label>
                        <div class="control-row">
                            <input type="color" value="#1d4ed8">
                            <div class="color-presets">
                                <button class="color-preset-btn" style="background-color: #1d4ed8"></button>
                                <button class="color-preset-btn" style="background-color: #2563eb"></button>
                                <button class="color-preset-btn" style="background-color: #3b82f6"></button>
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
                <button class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Download QR
                </button>
                <button class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                    Copy Link
                </button>
            </div>
            
            <!-- Email Form -->
            <div class="tab-card">
                <div class="form-header">
                    <h3 class="form-title">Email your card</h3>
                    <div class="form-counter" title="You can send up to 20 emails per day">
                        <span>12/20 sent today</span>
                    </div>
                </div>
                
                <form class="tab-form">
                    <div class="form-row">
                        <input type="text" placeholder="Name" required>
                        <input type="email" placeholder="Email" required>
                    </div>
                    <div class="form-row">
                        <textarea placeholder="Message (optional)" rows="3"></textarea>
                        <div class="char-counter">0/500</div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-back">← Go back</button>
                        <button type="submit" class="btn btn-primary">
                            Send
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
