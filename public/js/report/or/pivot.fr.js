(function() {
  var callWithJQuery;

  callWithJQuery = function(pivotModule) {
    if (typeof exports === "object" && typeof module === "object") {
      return pivotModule(require("jquery"));
    } else if (typeof define === "function" && define.amd) {
      return define(["jquery"], pivotModule);
    } else {
      return pivotModule(jQuery);
    }
  };

  callWithJQuery(function($) {
    var frFmt, frFmtInt, frFmtPct, nf, tpl;
    nf = $.pivotUtilities.numberFormat;
    tpl = $.pivotUtilities.aggregatorTemplates;
    frFmt = nf({
      thousandsSep: ",",
      decimalSep: "."
    });
    frFmtInt = nf({
      digitsAfterDecimal: 0,
      thousandsSep: ",",
      decimalSep: "."
    });
    frFmtPct = nf({
      digitsAfterDecimal: 1,
      scaler: 100,
      suffix: "%",
      thousandsSep: ",",
      decimalSep: "."
    });
    return $.pivotUtilities.locales.fr = {
      localeStrings: {
        renderError: "Une erreur est survenue en dessinant le tableau croisé.",
        computeError: "Une erreur est survenue en calculant le tableau croisé.",
        uiRenderError: "Une erreur est survenue en dessinant l'interface du tableau croisé dynamique.",
        selectAll: "Hundaa Filadhu",
        selectNone: "Omaa Hin Filatin",
        tooMany: "(trop de valeurs à afficher)",
        filterResults: "Filtrer les valeurs",
        totals: "Dimshaashaa",
        vs: "Vs",
        by: "by",
        apply: "Godhi",
        cancel: "Dhiisi"
      },
      aggregators: {
         "Ida`aama": tpl.sum(usFmt),
        "Lakkofsaan": tpl.count(frFmtInt),
        "Parsantidhaan": tpl.fractionOf(tpl.sum(), "total", frFmtPct),
         "Tarreessi": tpl.listUnique(", ")  
      },
      renderers: {
        "Table": $.pivotUtilities.renderers["Table"],
        "Table avec barres": $.pivotUtilities.renderers["Table Barchart"],
        "Carte de chaleur": $.pivotUtilities.renderers["Heatmap"],
        "Carte de chaleur par ligne": $.pivotUtilities.renderers["Row Heatmap"],
        "Carte de chaleur par colonne": $.pivotUtilities.renderers["Col Heatmap"]
      }
    };
  });

}).call(this);

//# sourceMappingURL=pivot.fr.js.map
