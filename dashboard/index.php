<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php?erro=acesso");
    exit();
}

include '../includes/conn.php';

// ID do usuário logado
$usuario_id = $_SESSION['id'];


// ==================================================
// TOTAL DE EPISÓDIOS
// ==================================================

$sql = "SELECT COUNT(*) AS total
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$total_crises = $resultado->fetch_assoc()['total'];

$stmt->close();


// ==================================================
// DURAÇÃO MÉDIA DOS EPISÓDIOS
// ==================================================

$sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, inicio, fim)) AS media
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$media_segundos = $resultado->fetch_assoc()['media'];

$stmt->close();


// Converte segundos para minutos
if ($media_segundos !== null) {
    $media_minutos = round($media_segundos / 60);
} else {
    $media_minutos = 0;
}


// ==================================================
// ÚLTIMA INTENSIDADE REGISTRADA
// ==================================================

$sql = "SELECT intensidade
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL
        AND intensidade IS NOT NULL
        ORDER BY fim DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$ultima_crise = $resultado->fetch_assoc();

$stmt->close();

if ($ultima_crise) {
    $ultima_intensidade = $ultima_crise['intensidade'];
} else {
    $ultima_intensidade = '-';
}


// ==================================================
// ÚLTIMO EPISÓDIO
// ==================================================

$sql = "SELECT inicio, fim, intensidade
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL
        ORDER BY fim DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$ultimo_episodio = $resultado->fetch_assoc();

$stmt->close();

// ==================================================
// DADOS DO GRÁFICO DE PIZZA
// ==================================================

$sql = "SELECT intensidade, COUNT(*) AS quantidade
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL
        AND intensidade IS NOT NULL
        GROUP BY intensidade
        ORDER BY intensidade";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();

$dados_intensidade = [];

while ($linha = $resultado->fetch_assoc()) {
    $dados_intensidade[] = $linha;
}

$stmt->close();


// ==================================================
// DADOS DO GRÁFICO DE LINHA
// ==================================================

$sql = "SELECT inicio, intensidade
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NOT NULL
        AND intensidade IS NOT NULL
        ORDER BY inicio ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();

$dados_linha = [];

while ($linha = $resultado->fetch_assoc()) {
    $dados_linha[] = $linha;
}

$stmt->close();

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard | PulseLog</title>

    <link
        rel="stylesheet"
        href="../assets/css/dashboard_index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <header>

        <h1>
            Pulse<span>Log</span>
        </h1>

        <nav>

            <span>
                Olá, <?= htmlspecialchars($_SESSION['nome']) ?>!
            </span>

            <a href="../auth/logout.php">
                Sair
            </a>

        </nav>

    </header>


    <main>

        <!-- ==============================
         BOAS-VINDAS
    =============================== -->

        <section class="welcome">

            <h2>
                Bem-vindo ao PulseLog
            </h2>

            <p>
                Aqui você poderá acompanhar seus registros.
            </p>

        </section>


        <!-- ==============================
         CARDS
    =============================== -->

        <section class="dashboard-cards">


            <!-- Total de episódios -->

            <div class="dashboard-card">

                <div class="card-icon">
                    📋
                </div>

                <div>

                    <span class="card-label">
                        Episódios registrados
                    </span>

                    <strong class="card-value">
                        <?= $total_crises ?>
                    </strong>

                </div>

            </div>


            <!-- Duração média -->

            <div class="dashboard-card">

                <div class="card-icon">
                    ⏱️
                </div>

                <div>

                    <span class="card-label">
                        Duração média
                    </span>

                    <strong class="card-value">

                        <?php if ($media_minutos > 0): ?>

                            <?= $media_minutos ?> min

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </strong>

                </div>

            </div>


            <!-- Última intensidade -->

            <div class="dashboard-card">

                <div class="card-icon">
                    📊
                </div>

                <div>

                    <span class="card-label">
                        Última intensidade
                    </span>

                    <strong class="card-value">
                        <?= htmlspecialchars((string) $ultima_intensidade) ?>
                    </strong>

                </div>

            </div>


            <!-- Último episódio -->

            <div class="dashboard-card">

                <div class="card-icon">
                    🕐
                </div>

                <div>

                    <span class="card-label">
                        Último episódio
                    </span>

                    <strong class="card-value">

                        <?php if ($ultimo_episodio): ?>

                            <?= date(
                                'd/m',
                                strtotime($ultimo_episodio['inicio'])
                            ) ?>

                        <?php else: ?>

                            -

                        <?php endif; ?>

                    </strong>

                </div>

            </div>


        </section>

        <!-- ==============================
     GRÁFICOS
=============================== -->

        <section class="charts-section">

            <div class="chart-card">

                <h2>
                    Distribuição das intensidades
                </h2>

                <p>
                    Veja quantos episódios foram registrados
                    em cada nível de intensidade.
                </p>

                <div class="chart-container">
                    <canvas id="graficoPizza"></canvas>
                </div>

            </div>


            <div class="chart-card">

                <h2>
                    Intensidade ao longo do tempo
                </h2>

                <p>
                    Acompanhe a intensidade registrada
                    em cada episódio.
                </p>

                <div class="chart-container">
                    <canvas id="graficoLinha"></canvas>
                </div>

            </div>

        </section>



        <!-- ==============================
         NFC
    =============================== -->

        <section class="dashboard-section">

            <h2>
                NFC
            </h2>

            <p>
                Cadastre um cartão NFC para começar.
            </p>

            <a
                href="../nfc/cadastrar_nfc.php"
                class="dashboard-button">
                Cadastrar cartão
            </a>

        </section>


        <!-- ==============================
         ÚLTIMO EPISÓDIO
    =============================== -->

        <section class="dashboard-section">

            <h2>
                Último episódio
            </h2>


            <?php if ($ultimo_episodio): ?>

                <div class="last-episode">

                    <p>
                        <strong>Início:</strong>

                        <?= date(
                            'd/m/Y H:i',
                            strtotime($ultimo_episodio['inicio'])
                        ) ?>
                    </p>

                    <p>
                        <strong>Fim:</strong>

                        <?= date(
                            'd/m/Y H:i',
                            strtotime($ultimo_episodio['fim'])
                        ) ?>
                    </p>

                    <p>
                        <strong>Intensidade:</strong>

                        <?= htmlspecialchars(
                            (string) $ultimo_episodio['intensidade']
                        ) ?>
                    </p>

                </div>

            <?php else: ?>

                <p>
                    Você ainda não possui episódios registrados.
                </p>

            <?php endif; ?>

        </section>


    </main>

    <script>
        // Dados vindos do PHP

        const dadosIntensidade =
            <?= json_encode($dados_intensidade) ?>;

        const dadosLinha =
            <?= json_encode($dados_linha) ?>;


        // ==========================================
        // GRÁFICO DE PIZZA
        // ==========================================

        const nomesIntensidade = {
            1: '1 · Muito leve',
            2: '2 · Leve',
            3: '3 · Moderada',
            4: '4 · Forte',
            5: '5 · Muito forte'
        };

        const labelsPizza = dadosIntensidade.map(
            function(item) {
                return nomesIntensidade[item.intensidade];
            }
        );

        const valoresPizza = dadosIntensidade.map(
            function(item) {
                return item.quantidade;
            }
        );


        new Chart(
            document.getElementById('graficoPizza'), {
                type: 'pie',

                data: {
                    labels: labelsPizza,

                    datasets: [{
                        data: valoresPizza
                    }]
                },

                options: {
                    responsive: true,

                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            }
        );


        // ==========================================
        // GRÁFICO DE LINHA
        // ==========================================

        const labelsLinha = dadosLinha.map(
            function(item) {

                const data = new Date(
                    item.inicio.replace(' ', 'T')
                );

                return data.toLocaleDateString('pt-BR');
            }
        );


        const valoresLinha = dadosLinha.map(
            function(item) {
                return Number(item.intensidade);
            }
        );


        new Chart(
            document.getElementById('graficoLinha'), {
                type: 'line',

                data: {
                    labels: labelsLinha,

                    datasets: [{
                        label: 'Intensidade',

                        data: valoresLinha,

                        tension: 0.3,

                        fill: false
                    }]
                },

                options: {
                    responsive: true,

                    scales: {
                        y: {
                            min: 1,
                            max: 5,

                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            }
        );
    </script>

</body>

</html>