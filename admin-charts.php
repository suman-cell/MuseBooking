<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

include 'db.php';

// Fetch daily bookings
$chartData = [];
$query = "SELECT DATE(booking_date) as day, COUNT(*) as bookings, SUM(total_price) as revenue 
          FROM bookings GROUP BY DATE(booking_date) ORDER BY day ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $chartData[] = $row;
}

// Fetch museum distribution
$museumData = [];
$museumQuery = "SELECT museum, COUNT(*) as count FROM bookings GROUP BY museum";
$museumResult = mysqli_query($conn, $museumQuery);
while ($row = mysqli_fetch_assoc($museumResult)) {
    $museumData[] = $row;
}

// Fetch status distribution
$statusData = [];
$statusQuery = "SELECT booking_status, COUNT(*) as count FROM bookings GROUP BY booking_status";
$statusResult = mysqli_query($conn, $statusQuery);
while ($row = mysqli_fetch_assoc($statusResult)) {
    $statusData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Charts</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --accent: #4895ef;
        --dark: #1b263b;
        --light: #f8f9fa;
        --success: #4cc9f0;
        --danger: #f72585;
        --warning: #f8961e;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }
    
    .container {
        max-width: 95%;
        margin: 0 auto;
        padding: 20px;
    }
    
    .logout {
        float: right;
        margin-bottom: 20px;
    }
    
    .logout a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        background: var(--danger);
        padding: 10px 20px;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(247, 37, 133, 0.3);
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .logout a:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(247, 37, 133, 0.4);
    }
    
    h2 {
        color: var(--dark);
        font-weight: 600;
        margin-bottom: 30px;
        font-size: 28px;
        position: relative;
        display: inline-block;
    }
    
    h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--primary);
        border-radius: 2px;
    }
    
    .chart-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .chart-wrapper {
        flex: 1;
        min-width: 300px;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .chart-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .chart-title {
        text-align: center;
        margin-bottom: 20px;
        color: var(--dark);
        font-weight: 500;
    }
    
    canvas {
        width: 100% !important;
        height: 300px !important;
    }
    
    .back-btn {
        display: inline-block;
        padding: 12px 25px;
        background: var(--primary);
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        margin-top: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    
    .back-btn:hover {
        background: var(--secondary);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }
    
    @media (max-width: 768px) {
        .chart-wrapper {
            min-width: 100%;
        }
    }
  </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <h2>📊 Analytics Dashboard</h2>

        <div class="chart-container">
            <div class="chart-wrapper">
                <h3 class="chart-title">Daily Bookings & Revenue</h3>
                <canvas id="bookingChart"></canvas>
            </div>
            
            <div class="chart-wrapper">
                <h3 class="chart-title">Museum Distribution</h3>
                <canvas id="museumChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <div class="chart-wrapper">
                <h3 class="chart-title">Booking Status Distribution</h3>
                <canvas id="statusChart"></canvas>
            </div>
            
            <div class="chart-wrapper">
                <h3 class="chart-title">Revenue Trend</h3>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <script>
        // Booking and Revenue Chart
        const labels = <?= json_encode(array_column($chartData, 'day')) ?>;
        const bookings = <?= json_encode(array_map('intval', array_column($chartData, 'bookings'))) ?>;
        const revenue = <?= json_encode(array_map('floatval', array_column($chartData, 'revenue'))) ?>;
        
        const bookingCtx = document.getElementById('bookingChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Bookings',
                        data: bookings,
                        backgroundColor: 'rgba(67, 97, 238, 0.2)',
                        borderColor: 'rgba(67, 97, 238, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Revenue (₹)',
                        data: revenue,
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointBackgroundColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Poppins'
                        },
                        padding: 12,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Bookings',
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Revenue (₹)',
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    }
                }
            }
        });
        
        // Museum Distribution Chart
        const museumLabels = <?= json_encode(array_column($museumData, 'museum')) ?>;
        const museumCounts = <?= json_encode(array_map('intval', array_column($museumData, 'count'))) ?>;
        
        const museumCtx = document.getElementById('museumChart').getContext('2d');
        new Chart(museumCtx, {
            type: 'doughnut',
            data: {
                labels: museumLabels,
                datasets: [{
                    data: museumCounts,
                    backgroundColor: [
                        'rgba(67, 97, 238, 0.8)',
                        'rgba(103, 114, 229, 0.8)',
                        'rgba(139, 131, 221, 0.8)',
                        'rgba(175, 148, 212, 0.8)',
                        'rgba(211, 165, 204, 0.8)'
                    ],
                    borderColor: 'white',
                    borderWidth: 2,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Poppins'
                        },
                        padding: 12,
                        usePointStyle: true
                    }
                },
                cutout: '65%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
        
        // Status Distribution Chart
        const statusLabels = <?= json_encode(array_column($statusData, 'booking_status')) ?>;
        const statusCounts = <?= json_encode(array_map('intval', array_column($statusData, 'count'))) ?>;
        
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Bookings',
                    data: statusCounts,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: 'white',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Poppins'
                        },
                        padding: 12,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    }
                }
            }
        });
        
        // Revenue Trend Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: revenue,
                    fill: true,
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Poppins'
                        },
                        padding: 12,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>