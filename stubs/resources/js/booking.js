window.initCalendarTooltip = function(){
    $(function(){
        console.log('initCalendarTooltip');
        document.querySelectorAll(".tooltip").forEach(e => e.remove());
        const tooltipTriggerList = document.querySelectorAll('.calendar-btn[title]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
}
window.addEventListener('initCalendarTooltip', window.initCalendarTooltip);
