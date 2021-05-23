<?php

use dosamigos\chartjs\ChartJs;

?>

<div class="col-sm-4">
    <?php
    echo ChartJs::widget([
        'type' => 'horizontalBar',
        'id' => 'structureBarThree',
        'options' => [
            'height' => 500,
            'width' => 500,
        ],
        'data' => [
            'radius' =>  "90%",
            'labels' => array_keys($pie_data), // Your labels
            'datasets' => [
                [
                    'data' =>  array_values($pie_data), // Your dataset
                    'label' => '',
                    'backgroundColor' => [
                        '#ADC3FF',
                        '#FF9A9A',
                        'rgba(190, 124, 145, 0.8)'
                    ],
                    'borderColor' =>  [
                        '#fff',
                        '#fff',
                        '#fff'
                    ],
                    'borderWidth' => 1,
                    'hoverBorderColor' => ["#999", "#999", "#999"],
                ]
            ]
        ],
        'clientOptions' => [
            'legend' => [
                'display' => false,
                'position' => 'bottom',
                'labels' => [
                    'fontSize' => 10,
                    'fontColor' => "#425062",
                ]
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => true
            ],
            'hover' => [
                'mode' => false
            ],
            'maintainAspectRatio' => false,

        ],
        'plugins' =>
        new \yii\web\JsExpression('
            [{
                afterDatasetsDraw: function(chart, easing) {
                    var ctx = chart.ctx;
                    chart.data.datasets.forEach(function (dataset, i) {
                        var meta = chart.getDatasetMeta(i);
                        if (!meta.hidden) {
                            meta.data.forEach(function(element, index) {
                                // Draw the text in black, with the specified font
                                ctx.fillStyle = "rgb(0, 0, 0)";
    
                                var fontSize = 13;
                                var fontStyle = "normal";
                                var fontFamily = "Helvetica";
                                ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
    
                                // Just naively convert to string for now
                                var dataString = dataset.data[index].toString();
    
                                // Make sure alignment settings are correct
                                ctx.textAlign = "center";
                                ctx.textBaseline = "middle";
    
                                var padding = 5;
                                var position = element.tooltipPosition();
                                ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                            });
                        }
                    });
                }
            }]')
    ])
    ?>
</div>