(function() {
    const devtools = /./;
    devtools.toString = function() {
        this.opened = true;
        showWarning();
    };

    const showWarning = () => {
        console.clear();
        console.log('%cDừng lại!', 'color: red; font-size: 42px; font-weight: bold;');
        console.log('%cTraffic thật không tool bảo hành vĩnh viễn, bao top, admin group chợ nghiện seo vui lòng liên hệ telegram:https://t.me/tainguyenseo', 'font-size: 16px;');
    };

    console.log('%c', devtools);
})();