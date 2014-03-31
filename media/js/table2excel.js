jQuery.fn.table2excel = function() {
   
    var excelData = [];
    var headerArr = [];
    var el = this;

    //header
    var tmpRow = [];

    jQuery(el).find('th').each(function() {
		tmpRow[tmpRow.length] = formatData(jQuery(this).html());
	});

    rowMaker(tmpRow);

    // actual data
    jQuery(el).find('tr').each(function() {
        var tmpRow = [];
        jQuery(this).find('td').each(function() {
            tmpRow[tmpRow.length] = formatData(jQuery(this).html());
        });
        rowMaker(tmpRow);
    });
    var myExcel = excelData.join('\n');
    return myExcel;

    function rowMaker(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(' ##br## ');
            excelData[excelData.length] = mystr + " ####BR#### ";
        }
    }
    function formatData(input) {
        // replace " with â€œ
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "â€œ");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return output;
    }
};