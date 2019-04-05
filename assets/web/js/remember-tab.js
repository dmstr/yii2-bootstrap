var activeTabIds = window.activeDmstrBootstrapTabIds;

console.log(activeTabIds);
for (var i = 0; i < activeTabIds.length; i++) {
  var activeTabId = activeTabIds[i];
  var activeTabsData = JSON.parse(window.localStorage.getItem("activeDmstrBootstrapTabs"));

  if (activeTabsData === null) {
    activeTabsData = {};
  }

  selectTab(activeTabIds);

  $(document).on("click","#" + activeTabId + " > li > a", function () {
    activeTabsData[activeTabId] = $(this).attr("href");
    window.localStorage.setItem("activeDmstrBootstrapTabs",JSON.stringify(activeTabsData));
  });
}

function selectTab(activeTabIds) {
  for (var i = 0; i < activeTabIds.length; i++) {
    if (activeTabsData[activeTabIds] !== undefined) {
      $("a[href=\"" + activeTabsData[activeTabIds] + "\"]").tab("show");
    }
  }
}


$(document).on("pjax:end", function () {
  selectTab(activeTabIds);
});


