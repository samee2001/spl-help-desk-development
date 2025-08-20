(function() {
    function setSelectValue(id, value) {
        var el = document.getElementById(id);
        if (!el) return;
        if (value !== undefined && value !== null && value !== '') {
            el.value = String(value);
            if (el.value !== String(value)) {
                var opt = el.querySelector('option[value="' + String(value).replace(/"/g, '\\"') + '"]');
                if (opt) opt.selected = true;
            }
        }
    }

    function applyCurrentValues() {
        setSelectValue("organization", document.getElementById("current_org")?.value || "");
        setSelectValue("contact", document.getElementById("current_contact")?.value || "");
        setSelectValue("assignee", document.getElementById("current_assignee")?.value || "");
        setSelectValue("priority", document.getElementById("current_priority")?.value || "");
        setSelectValue("category", document.getElementById("current_category")?.value || "");
    }

    // On full page load
    document.addEventListener("DOMContentLoaded", applyCurrentValues);

    // When offcanvas is shown (works if this partial is injected)
    var panel = document.getElementById("offcanvasRightPanel");
    if (panel) {
        panel.addEventListener("shown.bs.offcanvas", applyCurrentValues);
    }
})();