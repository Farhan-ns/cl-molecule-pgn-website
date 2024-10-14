@extends('adminlte::page')

@section('title', 'Grafik PGN 2024')

@section('content_header')
  <h1>
    <b>Grafik PGN 2024</b>
  </h1>
@stop

@section('content')
  <div class="d-flex justify-content-end">
  </div>

  @if (Session::has('message'))
    <div class="alert alert-primary">
      {{ Session::get('message') }}
    </div>
  @endif

  <div class="mt-2 card">
    <div class="card-body row d-flex justify-content-center">
      <div class="col-12 col-lg-5">
        <div class="d-flex flex-column align-items-center">
          <h3>Grafik Daftar Ulang (Scan)</h3>
          <canvas id="scan_chart" aria-label="chart" height="250" width="380"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-2 card">
    <div class="card-body row d-flex justify-content-center">
      <div class="col-12 col-lg-5">
        <div class="d-flex flex-column align-items-center">
          <h3>Grafik Konfirmasi Kehadiran</h3>
          <canvas id="attendance_chart" aria-label="chart" height="250" width="380"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-2 card">
    <div class="card-body row d-flex justify-content-center">
      <div class="col-12 col-lg-6">
        <div class="d-flex flex-column align-items-center">
          <h3>Berdasarkan Jenis Pelanggan</h3>
          <canvas id="membership_chart" height="350" width="580"></canvas>
        </div>
      </div>
      <div class="col-12 col-lg-6 mt-4 mt-md-0 mt-lg-0">
        <div class="d-flex flex-column align-items-center">
          <h3>Berdasarkan Area Pelanggan</h3>
          <canvas id="area_chart" height="350" width="580"></canvas>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@stop

@section('js')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


  <script>
    var scanChart = document.getElementById('scan_chart').getContext("2d");;
    const scanData = JSON.parse(@json($scanData));
    console.log(scanData.labels);
    console.log(scanData.datasets);

    var newScanChart = new Chart(scanChart, {
      type: "pie",
      data: {
        labels: scanData.labels,
        datasets: [{
          data: scanData.datasets,
          hoverOffset: 5,
          backgroundColor: ['#2980b9', '#e74c3c']
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: {
              generateLabels: (chart) => {
                const datasets = chart.data.datasets;
                return datasets[0].data.map((data, i) => ({
                  text: `${chart.data.labels[i]} ${data}`,
                  fillStyle: datasets[0].backgroundColor[i],
                  index: i
                }))
              }
            }

          }
        }
      }
    });

    //

    var attendanceChart = document.getElementById('attendance_chart').getContext("2d");;
    const attendanceData = JSON.parse(@json($attendanceData));

    var newAttendanceChart = new Chart(attendanceChart, {
      type: "pie",
      data: {
        labels: attendanceData.labels,
        datasets: [{
          data: attendanceData.datasets,
          hoverOffset: 5,
          backgroundColor: ['#2980b9', '#e74c3c', '#9aba57']
        }]
      },
      options: {
        plugins: {
          legend: {
            labels: {
              generateLabels: (chart) => {
                const datasets = chart.data.datasets;
                return datasets[0].data.map((data, i) => ({
                  text: `${chart.data.labels[i]} ${data}`,
                  fillStyle: datasets[0].backgroundColor[i],
                  index: i
                }))
              }
            }

          }
        }
      }
    });

    // ===========================================================================
    var membershipChart = document.getElementById('membership_chart').getContext("2d");;
    const membershipData = JSON.parse(@json($membershipData));

    var newMembershipChart = new Chart(membershipChart, {
      type: "bar",
      data: {
        labels: membershipData.labels,
        datasets: membershipData.datasets.map((item) => ({
          label: item.label,
          data: item.data,
          backgroundColor: item.backgroundColor,
        })),
      },
      plugins: [ChartDataLabels],
      options: {
        plugins: {
          datalabels: {
            anchor: 'end', // Position the labels at the end of the bars
            align: 'end', // Align the labels with the end of the bars
            color: 'black', // You can change the color if needed
            formatter: (value) => value, // This will display the value
          }
        },
      }
    });
    // ===========================================================================
    var areaChart = document.getElementById('area_chart').getContext("2d");;
    const areaData = JSON.parse(@json($areaData));

    var newAreaChart = new Chart(areaChart, {
      type: "bar",
      data: {
        labels: areaData.labels,
        datasets: areaData.datasets.map((item) => ({
          label: item.label,
          data: item.data,
          backgroundColor: item.backgroundColor,
        })),
      },
      plugins: [ChartDataLabels],
      options: {
        plugins: {
          datalabels: {
            anchor: 'end', // Position the labels at the end of the bars
            align: 'end', // Align the labels with the end of the bars
            color: 'black', // You can change the color if needed
            formatter: (value) => value, // This will display the value
          }
        },
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@stop
