/**
 * Bilohash Smart Popups - Frontend Script
 * Version: 1.0.0
 * Author: Ruslan Bilohash
 */

(function () {
    'use strict';

    if (typeof biloSmartPopup === 'undefined' || !biloSmartPopup.title) {
        return;
    }

    const p = biloSmartPopup;

    // Не показувати повторно
    if (p.prevent_reopen && localStorage.getItem('bilo_popup_closed') === '1') {
        return;
    }

    let popupShown = false;

    const popup = document.createElement('div');
    popup.id = 'bilo-smart-popup';
    popup.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.75);
        width: ${p.popup_width || '460px'};
height: ${p.popup_height || 'auto'};
max-height: 85vh;           // щоб не вилізало за межі екрану
overflow-y: auto;           // якщо контент великий
        max-width: 94vw;
        background: ${p.background};
        border-radius: 24px;
        box-shadow: 0 30px 90px rgba(0, 245, 255, 0.45);
        overflow: hidden;
        z-index: 999999;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 3px solid ${p.accent};
        font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color: ${p.text_color};           /* ← Колір тексту для всього попапу */
    `;

    const branding = p.show_branding 
        ? `<div style="text-align:center;font-size:12.5px;color:rgba(255,255,255,0.45);padding:14px;background:rgba(0,0,0,0.25);">Powered by Bilohash Smart Popups</div>` 
        : '';

    let countdownHTML = '';
    if (p.show_countdown && p.countdown_end) {
        countdownHTML = `<div id="bilo-countdown" style="background:rgba(0,0,0,0.3);padding:12px;text-align:center;font-size:18px;font-weight:700;color:${p.accent};"></div>`;
    }

    popup.innerHTML = `
        <div style="padding:32px 28px 24px; text-align:center; position:relative; color: ${p.text_color};">
            <button id="bilo-popup-close" style="position:absolute;top:16px;right:20px;background:none;border:none;font-size:32px;color:#aaa;cursor:pointer;">×</button>
            
            <div style="font-size:48px;margin-bottom:12px;">${p.icon}</div>
            
            <h2 style="margin:0 0 18px 0; color:${p.title_color}; font-size:26px; font-weight:700;">
                ${p.title}
            </h2>
            
            <div style="line-height:1.65; font-size:16.5px; margin-bottom:22px; color: ${p.text_color};">
                ${p.content}
            </div>
            
            ${countdownHTML}
            
            <a id="bilo-popup-button" href="${p.button_link}" 
               style="display:inline-block; background:linear-gradient(90deg, ${p.accent}, #0099ff); 
                      color:#000; padding:17px 38px; font-size:17px; font-weight:700; 
                      border-radius:50px; text-decoration:none; margin-top:8px;
                      box-shadow:0 10px 30px rgba(0,245,255,0.4);">
                ${p.button_text}
            </a>
        </div>
        ${branding}
    `;

    document.body.appendChild(popup);

    const closeBtn = document.getElementById('bilo-popup-close');
    const actionBtn = document.getElementById('bilo-popup-button');

    function showPopup() {
        if (popupShown) return;
        popupShown = true;
        popup.style.opacity = '1';
        popup.style.transform = 'translate(-50%, -50%) scale(1)';
    }

    function hidePopup() {
        popup.style.opacity = '0';
        popup.style.transform = 'translate(-50%, -50%) scale(0.75)';
        
        if (p.prevent_reopen) {
            localStorage.setItem('bilo_popup_closed', '1');
        }
        setTimeout(() => popup.remove(), 500);
    }

    closeBtn.addEventListener('click', hidePopup);
    actionBtn.addEventListener('click', hidePopup);

    // Countdown
    if (p.show_countdown && p.countdown_end) {
        const countdownEl = document.getElementById('bilo-countdown');
        const endTime = new Date(p.countdown_end).getTime();

        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "Offer Expired";
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
        }, 1000);
    }

    // Trigger
    function initTrigger() {
        if (p.trigger === 'time') {
            setTimeout(showPopup, Math.max(1200, p.delay));
        } else if (p.trigger === 'scroll') {
            let scrolled = false;
            window.addEventListener('scroll', () => {
                if (scrolled) return;
                if (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight) > 0.5) {
                    scrolled = true;
                    showPopup();
                }
            });
        } else if (p.trigger === 'exit') {
            document.addEventListener('mouseleave', (e) => {
                if (e.clientY < 0 && !popupShown) showPopup();
            });
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && popupShown) hidePopup();
    });

    initTrigger();

    console.log('✅ Bilohash Smart Popups initialized');
})();
