<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Banking Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            margin: 0;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .back-link:hover {
            background: #555;
        }

        @media (max-width: 700px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Banking Dashboard</h1>

    <div class="cards">

        <div class="card">
            <h2>Total Accounts</h2>
            <p id="totalAccounts">Loading...</p>
        </div>

        <div class="card">
            <h2>Total Money in Bank</h2>
            <p id="totalMoney">Loading...</p>
        </div>

        <div class="card">
            <h2>Transactions Today</h2>
            <p id="totalTransactions">Loading...</p>
        </div>

    </div>

    <a href="{{ route('transactions.index') }}" class="back-link">
        View Transactions
    </a>

</div>

<script>

    function loadDashboardStats() {

        fetch('/api/dashboard/stats')
            .then(response => response.json())
            .then(data => {

                document.getElementById('totalAccounts').textContent =
                    data.total_accounts;

                document.getElementById('totalMoney').textContent =
                    '₱' + data.total_money_in_bank;

                document.getElementById('totalTransactions').textContent =
                    data.total_transactions_today;

            })
            .catch(error => {
                console.error('Error loading dashboard:', error);
            });
    }

    // Load data when the page opens
    loadDashboardStats();

    // Update data every 30 seconds
    setInterval(loadDashboardStats, 30000);

</script>

</body>
</html>