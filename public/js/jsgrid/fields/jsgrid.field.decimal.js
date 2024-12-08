(function(jsGrid, $, undefined) {
    function DecimalField(config) {
        jsGrid.fields.number.call(this, config);
    }
    DecimalField.prototype = new jsGrid.fields.number({
        filterValue: function() {
            return this.filterControl.val()
            ? parseFloat(this.filterControl.val() || 0, 10)
            : undefined;
        },
        insertValue: function() {
            return this.insertControl.val()
            ? parseFloat(this.insertControl.val() || 0, 10)
            : undefined;
        },
        editValue: function() {
            return this.editControl.val()
            ? parseFloat(this.editControl.val() || 0, 10)
            : undefined;
        }
    });
    jsGrid.fields.decimal = jsGrid.DecimalField = DecimalField;
}(jsGrid, jQuery));