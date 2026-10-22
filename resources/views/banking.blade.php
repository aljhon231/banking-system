<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Simple Banking System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .deposit {
            background: #198754;
            color: white;
        }

        .withdraw {
            background: #dc3545;
            color: white;
        }

        .transfer {
            background: #0d6efd;
            color: white;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🏦 Simple Banking System</h1>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error">
            <strong>Please fix the following:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- Deposit -->
    <div class="card">

        <h2>Deposit</h2>

        <form action="{{ route('deposit') }}" method="POST">

            @csrf

            <label>Account Number</label>

            <input
                type="text"
                name="account_number"
                placeholder="Enter account number"
                required
            >

            <label>Amount</label>

            <input
                type="number"
                name="amount"
                step="0.01"
                min="1"
                placeholder="Enter amount"
                required
            >

            <button type="submit" class="deposit">
                Deposit
            </button>

        </form>

    </div>


    <!-- Withdrawal -->
    <div class="card">

        <h2>Withdrawal</h2>

        <form action="{{ route('withdraw') }}" method="POST">

            @csrf

            <label>Account Number</label>

            <input
                type="text"
                name="account_number"
                placeholder="Enter account number"
                required
            >

            <label>Amount</label>

            <input
                type="number"
                name="amount"
                step="0.01"
                min="1"
                placeholder="Enter amount"
                required
            >

            <button type="submit" class="withdraw">
                Withdraw
            </button>

        </form>

    </div>


    <!-- Transfer -->
    <div class="card">

        <h2>Transfer Money</h2>

        <form action="{{ route('transfer') }}" method="POST">

            @csrf

            <label>From Account</label>

            <input
                type="text"
                name="from_account"
                placeholder="Sender account number"
                required
            >

            <label>To Account</label>

            <input
                type="text"
                name="to_account"
                placeholder="Receiver account number"
                required
            >

            <label>Amount</label>

            <input
                type="number"
                name="amount"
                step="0.01"
                min="1"
                placeholder="Enter amount"
                required
            >

            <button type="submit" class="transfer">
                Transfer
            </button>

        </form>

    </div>

</div>

</body>
</html>