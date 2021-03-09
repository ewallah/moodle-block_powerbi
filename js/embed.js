$(function () {
    window.console.log('we have been started');
    var embedinfo = document.querySelector("#embedinfo");
    var embedurl = embedinfo.dataset.url;
    var reportId = embedinfo.dataset.reportid;
    var token = embedinfo.dataset.token;

    var embedContainer = $("#embedContainer")[0];

    powerbi.bootstrap(embedContainer, { type: "report" });

    var models = window["powerbi-client"].models;
    var permissions = models.Permissions.All;
    var filterselements = $(".filters");
    var filters = [];
    if (filterselements != undefined) {
        l = filterselements.length;
        for (i = 0; i < l; i++) {
            filters.push({
              $schema: "http://powerbi.com/product/schema#basic",
              target: {
                table: filterselements[i].dataset.table,
                column: filterselements[i].dataset.field
              },
              operator: "In",
              values: [filterselements[i].dataset.value],
              filterType: 1 // pbi.models.FilterType.BasicFilter
            });
        }
    }
    const config = {
        type: "report",
        tokenType: models.TokenType.Embed,
        accessToken: token,
        embedUrl: embedurl,
        id: reportId,
        permissions: permissions,
        filters: filters,
        settings: {
          filterPaneEnabled: false,
          layoutType: models.LayoutType.Custom,
          customLayout: {
            displayOption: models.DisplayOption.FitToWidth,
          },
        },
    };

    var report = powerbi.embed(embedContainer, config);
    // Triggers when a report schema is successfully loaded
    report.on("loaded", function () {
        window.console.log("Report load successful");
    });

    // Triggers when a report is successfully embedded in UI
    report.on("rendered", function () {
        window.console.log("Report render successful");
    });


    // Clear any other error handler event
    report.off("error");

    // Below patch of code is for handling errors that occur during embedding
    report.on("error", function (event) {
        var errorMsg = event.detail;

        // Use errorMsg variable to log error in any destination of choice
        window.console.error(errorMsg);
        return;
    });
});
