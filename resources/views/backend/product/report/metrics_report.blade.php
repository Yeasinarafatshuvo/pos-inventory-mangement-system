@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
@section('content')
<style>

</style>

<div class="col-md-12 row" id="divName">
    <div class="col-md-4">
        <table class="table table-bordered">
            <tr style="text-align:center;">
                <th colspan="2">Number of order by Month</th>
            </tr>
            <tr>
                <th>Month-Year</th>
                <th>Num Orders</th>
            </tr>
            @php
            $dates = array();
            $orders = "";
            $pie_orders = array();
            @endphp
            @foreach($order_monthly_wise as $order)
            <tr>
                <td>{{$order->new_date}}</td>
                <td>{{$order->data}}</td>
            </tr>
            @php
            array_push($dates,$order->new_date);
            array_push($pie_orders,$order->data);
            $orders .= $order->data.',';
            @endphp
            @endforeach
        </table>
    </div>
    
    <div class="col-md-4 border">
        <div class="fs-12 fw-700 mb-4">{{ translate('Num Orders Vs Month-Year') }}</div>
        <canvas id="graph-2" class="w-100" height="300"></canvas>
    </div>
    <div class="col-md-4 border">
        <div id="piechart" style="height: 300px;"></div>
    </div>
</div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
// $(document).ready(function () {
//     $('#dtBasicExample').DataTable();
//     $('.dataTables_length').addClass('bs-select');

// });
$(function() {
    $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
$(function() {
    $("#datepickertwo").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});



function printpage(divName) {

    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    $('.dataTables_filter').remove();
    $('#dtBasicExample_length').remove();
    $('#dtBasicExample_info').remove();
    $('#dtBasicExample_paginate').remove();
    window.print();

    document.body.innerHTML = originalContents;

}

AIZ.plugins.chart('#graph-2', {
            type: 'bar',
            data: {
                labels: [<?php foreach($dates as $date){echo '"'.$date.'"'.',';}?>],
                datasets: [{
                    label: '{{ translate('Sales ($)') }}',
                    data: [
                        <?php  echo $orders; ?>
                    ],
                    backgroundColor: '#DD4124',
                    borderColor: '#DD4124',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#fff',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Roboto',
                            fontSize: 10,
                            beginAtZero: true
                        },
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#fff'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Roboto',
                            fontSize: 10
                        },
                        barThickness: 20,
                        barPercentage: .5,
                        categoryPercentage: .5,
                    }],
                },
                legend: {
                    display: false
                }
            }
        });
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Month-Year', 'Orders'],
          <?php 
          foreach($dates as $key=>$date){
            echo "['".$date."',".$pie_orders[$key]."],";
          } 
          ?>

        ]);

        var options = {
          title: 'Monthly Basis Orders'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
@endsection