$(function () {
    window.console.log('we have been started');
    var embedinfo = document.querySelector("#embedinfo");
    console.log(embedinfo);
    var embedurl = embedinfo.dataset.url;
    var reportId = embedinfo.dataset.reportid;
    var token = embedinfo.dataset.token;
    window.console.log(reportId);

    var embedContainer = $("#embedContainer")[0];

    powerbi.bootstrap(embedContainer, { type: "report" });

    var models = window["powerbi-client"].models;
    var permissions = models.Permissions.All;

    const config = {
        type: "report",
        tokenType: models.TokenType.Embed,
        accessToken: token,
        embedUrl: embedurl,
        id: reportId,
        permissions: permissions,
        settings: {
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
