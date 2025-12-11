@extends('templates.app')

@section('content')
    <div class="container mt-4">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {!! Session::get('success') !!}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <h5>Statistik Artikel</h5>
                <canvas id="chartBar" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $.ajax({
                url: "{{ route('admin.posts.chart') }}",
                method: 'GET',
                success: function(response) {
                    showArticleChart(response);
                },
                error: function(err) {
                    console.error('Error:', err);
                    alert('Gagal mengambil data chart!');
                }
            });

            function showArticleChart(data) {
                const ctx = document.getElementById('chartBar');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Total Artikel', 'Published', 'Draft'],
                        datasets: [{
                            label: 'Jumlah Artikel',
                            data: [data.total, data.published, data.draft],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
