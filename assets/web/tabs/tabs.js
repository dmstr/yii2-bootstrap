var hasStorage = (function() {
    try {
        window.localStorage.setItem('tabTest', 'tabTest');
        window.localStorage.removeItem('tabTest');
        return true;
    } catch (exception) {
        return false;
    }
}());

// get sluged dockument URL
function getControllerId() {
    var currentUrl = document.URL;
    return currentUrl
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}
// set localstorage item for an element
function setStorage(elem) {
    var activeTab     = jQuery(elem).attr("href");
    if (hasStorage) {
        window.localStorage.setItem("_bs_activeTab_" + getControllerId(), activeTab);
    }
}
// get this localstorage item
function initialSelect() {
    if (hasStorage) {
        var activeTab = window.localStorage.getItem("_bs_activeTab_" + getControllerId());
        if (activeTab !== "") {
            jQuery("[href='" + activeTab + "']").tab("show");
        }
    }
}
