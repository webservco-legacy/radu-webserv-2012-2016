    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src='https://www.google.com/jsapi'></script>
    
    <script>
    //<![CDATA[ 
    
    var ie = (function() {

        var v = 3, div = document.createElement('div'), a = div.all || [];

        while (div.innerHTML = '<!--[if gt IE '+(++v)+']><br><![endif]-->', a[0]); 

        return v > 4 ? v : !v;

    }());
    
    
    google.load('visualization', '1', {'packages': ['geochart', 'table']});
        //google.setOnLoadCallback(drawRegionsMap);
        
        var data;
        var options;
        var Geochart;
        var table;
        
        function drawMapAndTable() {
            
            
            data = new google.visualization.DataTable();
            data.addColumn('string', 'Country');
            data.addColumn('number', '');
            data.addColumn({type:'string', role:'tooltip'});
            
            <?php
            $rows_a = array();
            $rows = NULL;
            
            foreach ($tam89 as $country => $data) {

                $rows_a[] = "['{$country}', 1, ' ']";
            }
            $rows = '[' . implode(",\n", $rows_a) . ']';
            echo "data.addRows({$rows});";
            ?>
            
            <?php /*
            data = google.visualization.arrayToDataTable([
                ['Country', ''],
            <?php foreach ($tam89 as $country => $data) { ?>
                ['<?php echo $country; ?>', 1],
            <?php } ?>
            ]);
            */ ?>
            
            //dirty, but then again so is ie
            
            if (ie !== false) {
            
            options = {
                colorAxis: { minValue : 1, maxValue : 2, colors : ['#fcc', '#c00']},
                backgroundColor: '#333',
                datalessRegionColor: '#ddd',
                legend: 'none',
                tooltip: {textStyle: {color: '#222'}, showColorCode: false}
                };
            
            } else {
            
            options = {
                colorAxis: { minValue : 1, maxValue : 2, colors : ['#fcc', '#c00']},
                backgroundColor: { fill:'transparent' },
                datalessRegionColor: '#ddd',
                legend: 'none',
                tooltip: {textStyle: {color: '#222'}, showColorCode: false}
                };
            
            }			

            var Geochart = new google.visualization.GeoChart(document.getElementById('map'));

            Geochart.draw(data, options);

            table = new google.visualization.Table(document.getElementById('legend'));
            var tableView = new google.visualization.DataView(data);

            tableView.setColumns([0]);
            table.draw(tableView, {showRowNumber: true});
            
            google.visualization.events.addListener(table, 'select', function () {
                
                Geochart.setSelection(table.getSelection());
               
                var selection = table.getSelection();
                if (selection.length > 0) {

                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, {
                        type: 'number',
                        label: data.getColumnLabel(1),
                        calc: function (dt, row) {
                            return (selection[0].row == row) ? 2 : 1;
                        }
                    }, 2 
                    //{type:'string', role:'tooltip'}
                    ]);
                    
                    Geochart.draw(view, options);
                    
                    var selectedCountry = data.getValue(selection[0].row, 0);

                    $.post("ajax.php", {
                        cmd : 'get_details',
                        country : selectedCountry
                    }, function(response) {

                        $("#details").html(response);
                    });				
                }
                else {
                    Geochart.draw(data, options);
                }
            });
            
            Geochart.draw(data, options);
            
            google.visualization.events.addListener(Geochart, 'select', function() {
                
                table.setSelection(Geochart.getSelection());
                
                var selection = Geochart.getSelection();
                
                if (selection.length > 0) {
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, {
                        type: 'number',
                        label: data.getColumnLabel(1),
                        calc: function (dt, row) {
                            return (selection[0].row == row) ? 2 : 1;
                        }
                        }, 2
                        //{type:'string', role:'tooltip'}
                    ]);
                    
                    Geochart.draw(view, options);

                    var selectedCountry = data.getValue(selection[0].row, 0);
                    
                    $.post("ajax.php", {

                        cmd : 'get_details',
                        country : selectedCountry
                    }, function(response) {

                        $("#details").html(response);
                    });	
                    
                }
                else {
                    Geochart.draw(data, options);
                }
                
                <?php /*
                var selectedItem = chart.getSelection()[0];
                if (selectedItem) {
                    var selectedCountry = data.getValue(selectedItem.row, 0);
                    
                    $("#legend").html(selectedCountry);
                }
                */ ?>
                
            });
            
            Geochart.draw(data, options);
            
            <?php /*  assumes:
             *    chart is the chart object to draw
             *    'visualization' is the name of the chart's div
             *  hooks the onMouseOver function to the chart's iframe body
             *  finds the 3rd-to-last text element (the category column label in the tooltip)
             *  and sets it to an empty string
             *  fires on every sub-element in the chart, so the tooltip never shows the label
             *  
             *  call this after instantiating the chart object but before drawing the chart
             *
            //google.visualization.events.addListener(chart, 'ready', function() {
                //$('#map').find('iframe').contents().find('body').mouseenter(function() {
                    //$(this).find('text').last().prev().prev().text('test');
                //});
            //});
            */ ?>
        };
        
        
    $(document).ready(function() {
        
        $("#help_btn").hover(function() {
            $("#help").css('display', 'block');
        }, function() {
            $("#help").css('display', 'none');
        }
        );
        
        drawMapAndTable();
    });
    //]]> 
    </script>
