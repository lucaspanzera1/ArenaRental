<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/conta.css?v=<?= time() ?>">
</head>

<body>

    <?php include '../layouts/header.php'; ?>
    <?php include '../layouts/verification.php'; ?>

    <main>
        <div id="Info">
            <h1>Conta</h1>
            <h2> <?php  $nomeCompleto = htmlspecialchars($client->getName());
                $primeiroNome = explode(' ', $nomeCompleto)[0];
                echo $primeiroNome; ?>, <?php echo htmlspecialchars($client->getEmail());  ?></h2>
        </div>

        <section id="Quadrados">
            <div class="quadrado">
                <a href="perfil.php">
                    <svg width="96" height="96" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_214_45)">
                            <path
                                d="M48.5 23C51.25 23 53.5 25.25 53.5 28C53.5 30.75 51.25 33 48.5 33C45.75 33 43.5 30.75 43.5 28C43.5 25.25 45.75 23 48.5 23ZM71 40.5H56V73H51V58H46V73H41V40.5H26V35.5H71V40.5Z"
                                fill="black" />
                        </g>
                        <defs>
                            <clipPath id="clip0_214_45">
                                <rect width="96" height="96" />
                            </clipPath>
                        </defs>
                    </svg>
                    <h5>Acessar Perfil</h5>
                    <h6>Seu perfil.</h6>
                </a>
            </div>
            <div class="quadrado">
                <a href="editar_perfil.php">
                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M48.9474 69.3158V65.6974C48.9474 64.9254 49.1042 64.1897 49.4178 63.4901C49.7314 62.7906 50.1535 62.1754 50.6842 61.6447L64.9408 47.3882C65.375 46.9539 65.8575 46.6404 66.3882 46.4474C66.9189 46.2544 67.4496 46.1579 67.9803 46.1579C68.5592 46.1579 69.114 46.2664 69.6447 46.4836C70.1754 46.7007 70.6579 47.0263 71.0921 47.4605L73.7697 50.1382C74.1557 50.5724 74.4572 51.0548 74.6743 51.5855C74.8915 52.1162 75 52.6469 75 53.1776C75 53.7083 74.9035 54.2511 74.7105 54.8059C74.5175 55.3607 74.2039 55.8553 73.7697 56.2895L59.5132 70.5461C58.9825 71.0768 58.3673 71.4868 57.6678 71.7763C56.9682 72.0658 56.2325 72.2105 55.4605 72.2105H51.8421C51.0219 72.2105 50.3344 71.9331 49.7796 71.3783C49.2248 70.8235 48.9474 70.136 48.9474 69.3158ZM20 66.4211V61.2105C20 59.5702 20.4221 58.0625 21.2664 56.6875C22.1107 55.3125 23.2325 54.2632 24.6316 53.5395C27.6228 52.0439 30.6623 50.9221 33.75 50.1743C36.8377 49.4265 39.9737 49.0526 43.1579 49.0526C44.943 49.0526 46.7039 49.1612 48.4408 49.3783C50.1776 49.5954 51.9145 49.9452 53.6513 50.4276L45.6908 58.3882C44.8706 59.2083 44.2434 60.1491 43.8092 61.2105C43.375 62.2719 43.1579 63.3816 43.1579 64.5395V69.3158H22.8947C22.0746 69.3158 21.3871 69.0384 20.8322 68.4836C20.2774 67.9287 20 67.2412 20 66.4211ZM67.9803 56L70.6579 53.1776L67.9803 50.5L65.2303 53.25L67.9803 56ZM43.1579 46.1579C39.9737 46.1579 37.2478 45.0241 34.9803 42.7566C32.7127 40.489 31.5789 37.7632 31.5789 34.5789C31.5789 31.3947 32.7127 28.6689 34.9803 26.4013C37.2478 24.1338 39.9737 23 43.1579 23C46.3421 23 49.068 24.1338 51.3355 26.4013C53.6031 28.6689 54.7368 31.3947 54.7368 34.5789C54.7368 37.7632 53.6031 40.489 51.3355 42.7566C49.068 45.0241 46.3421 46.1579 43.1579 46.1579Z"
                            fill="black" />
                    </svg>
                    <h5>Informações pessoais</h5>
                    <h6>Detalhes e informações pessoais.</h6>
                </a>
            </div>

            <div class="quadrado">
                <a href="alterar_senha.php">

                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_220_2)">
                            <path
                                d="M28.0455 56.4545H68.9545V60.5455H28.0455V56.4545ZM30.3977 48.1705L32.1364 45.1432L33.875 48.1705L36.5341 46.6364L34.7955 43.6091H38.2727V40.5409H34.7955L36.5341 37.5341L33.875 36L32.1364 39.0068L30.3977 36L27.7386 37.5341L29.4773 40.5409H26V43.6091H29.4773L27.7386 46.6364L30.3977 48.1705ZM44.1023 46.6364L46.7614 48.1705L48.5 45.1432L50.2386 48.1705L52.8977 46.6364L51.1591 43.6091H54.6364V40.5409H51.1591L52.8977 37.5341L50.2386 36L48.5 39.0068L46.7614 36L44.1023 37.5341L45.8409 40.5409H42.3636V43.6091H45.8409L44.1023 46.6364ZM71 40.5409H67.5227L69.2614 37.5341L66.6023 36L64.8636 39.0068L63.125 36L60.4659 37.5341L62.2045 40.5409H58.7273V43.6091H62.2045L60.4659 46.6364L63.125 48.1705L64.8636 45.1432L66.6023 48.1705L69.2614 46.6364L67.5227 43.6091H71V40.5409Z"
                                fill="black" />
                        </g>
                        <defs>
                            <clipPath id="clip0_220_2">
                                <rect width="96" height="96" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <h5>Alterar senha</h5>
                    <h6>Altere sua senha.</h6>
                </a>
            </div>

            <div class="quadrado">
                <a href="reservas.php">

                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_545_2)">
                            <path
                                d="M66 28H63.5V23H58.5V28H38.5V23H33.5V28H31C28.225 28 26.025 30.25 26.025 33L26 68C26 70.75 28.225 73 31 73H66C68.75 73 71 70.75 71 68V33C71 30.25 68.75 28 66 28ZM66 68H31V43H66V68ZM41 53H36V48H41V53ZM51 53H46V48H51V53ZM61 53H56V48H61V53ZM41 63H36V58H41V63ZM51 63H46V58H51V63ZM61 63H56V58H61V63Z"
                                fill="black" />
                        </g>
                        <defs>
                            <clipPath id="clip0_545_2">
                                <rect width="96" height="96" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>


                    <h5>Reservas</h5>
                    <h6>Minhas reservas.</h6>
                </a>
            </div>

            <?php if ($client->getType() === 'Dono'): ?>
            <div class="quadrado">
                <a href="../owner/gerenciador.php">
                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M46.25 61C42.5 61 39.3125 59.6875 36.6875 57.0625C34.0625 54.4375 32.75 51.25 32.75 47.5C32.75 47.0875 32.7688 46.675 32.8063 46.2625C32.8438 45.85 32.9 45.4375 32.975 45.025C32.75 45.1 32.525 45.1563 32.3 45.1938C32.075 45.2313 31.85 45.25 31.625 45.25C30.05 45.25 28.7187 44.7062 27.6312 43.6187C26.5437 42.5312 26 41.2 26 39.625C26 38.05 26.516 36.7187 27.548 35.6312C28.5785 34.5437 29.8813 34 31.4563 34C32.6938 34 33.809 34.3472 34.802 35.0417C35.7965 35.7347 36.5 36.625 36.9125 37.7125C38.15 36.5875 39.566 35.6875 41.1605 35.0125C42.7535 34.3375 44.45 34 46.25 34H68.75C69.3875 34 69.9215 34.2153 70.352 34.6458C70.784 35.0778 71 35.6125 71 36.25V40.75C71 41.3875 70.784 41.9215 70.352 42.352C69.9215 42.784 69.3875 43 68.75 43H59.75V47.5C59.75 51.25 58.4375 54.4375 55.8125 57.0625C53.1875 59.6875 50 61 46.25 61ZM31.625 41.875C32.2625 41.875 32.7972 41.659 33.2292 41.227C33.6597 40.7965 33.875 40.2625 33.875 39.625C33.875 38.9875 33.6597 38.4527 33.2292 38.0207C32.7972 37.5902 32.2625 37.375 31.625 37.375C30.9875 37.375 30.4535 37.5902 30.023 38.0207C29.591 38.4527 29.375 38.9875 29.375 39.625C29.375 40.2625 29.591 40.7965 30.023 41.227C30.4535 41.659 30.9875 41.875 31.625 41.875ZM46.25 52C47.4875 52 48.5473 51.559 49.4293 50.677C50.3098 49.7965 50.75 48.7375 50.75 47.5C50.75 46.2625 50.3098 45.2027 49.4293 44.3207C48.5473 43.4402 47.4875 43 46.25 43C45.0125 43 43.9535 43.4402 43.073 44.3207C42.191 45.2027 41.75 46.2625 41.75 47.5C41.75 48.7375 42.191 49.7965 43.073 50.677C43.9535 51.559 45.0125 52 46.25 52Z"
                            fill="black" />
                    </svg>
                    <h5>Gerenciador de Quadras</h5>
                    <h6>Gerencie suas quadras</h6>
                </a>
            </div>

            <div class="quadrado">
                <a href="../owner/metrica.php">
                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M29.5 38.5H30.1429C32.6179 38.5 34.6429 40.525 34.6429 43V65.5C34.6429 67.975 32.6179 70 30.1429 70H29.5C27.025 70 25 67.975 25 65.5V43C25 40.525 27.025 38.5 29.5 38.5ZM47.5 25C49.975 25 52 27.025 52 29.5V65.5C52 67.975 49.975 70 47.5 70C45.025 70 43 67.975 43 65.5V29.5C43 27.025 45.025 25 47.5 25ZM65.5 50.7143C67.975 50.7143 70 52.7393 70 55.2143V65.5C70 67.975 67.975 70 65.5 70C63.025 70 61 67.975 61 65.5V55.2143C61 52.7393 63.025 50.7143 65.5 50.7143Z"
                            fill="black" />
                    </svg>
                    <h5>Estatísticas</h5>
                    <h6>Analise suas métricas.</h6>
                </a>
            </div>
            <?php endif; ?>
            <div class="quadrado">
                <a href="deletar.php">
                    <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_221_5)">
                            <path
                                d="M68 32.0286L63.9714 28L48 43.9714L32.0286 28L28 32.0286L43.9714 48L28 63.9714L32.0286 68L48 52.0286L63.9714 68L68 63.9714L52.0286 48L68 32.0286Z"
                                fill="black" />
                        </g>
                        <defs>
                            <clipPath id="clip0_221_5">
                                <rect width="96" height="96" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <h5>Desativar conta</h5>
                    <h6>Excluir conta.</h6>
                </a>
            </div>
        </section>
    </main>
    <script src="../java/dark.js"></script>
</body>

</html>