<?php
$id = htmlspecialchars($_GET['id'] ?? '');
$user = htmlspecialchars($_GET['user'] ?? 'Invité');
$admin = isset($_GET['admin']) ? true : false;
$pwd = isset($_GET['password']) ? htmlspecialchars($_GET['password']) : '';

if (!$id) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Salle <?= $id ?> - Planning Poker</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body onload="initRoom('<?= $id ?>','<?= $user ?>', <?= $admin ? 'true' : 'false' ?>, '<?= $pwd ?>')">
  <header class="topbar">
    <h1>Salle : <span id="roomId"><?= $id ?></span></h1>
    <div>Pseudo : <strong id="userName"><?= $user ?></strong> <?= $admin ? '(admin)' : '' ?></div>
  </header>

  <main class="container">
    <section class="card">
      <h2>User Story</h2>
      <?php if ($admin): ?>
        <input id="storyInput" placeholder="Décris la User Story..." />
        <button onclick="setStory()">Valider</button>
      <?php endif; ?>
      <p id="currentStory" class="storyText">Aucune story définie.</p>
    </section>

    <section class="card">
      <h2>Cartes</h2>
      <div id="cards" class="cards"></div>
      <div class="actions" id="voteActions">
        <p>Vote sélectionné : <span id="myVote">—</span></p>
      </div>
    </section>

    <section class="card">
      <h2>Participants & Résultats</h2>
      <div id="participants"></div>
      <div id="results"></div>

      <?php if ($admin): ?>
        <div style="margin-top:10px;">
          <button onclick="reveal()">Révéler</button>
          <button onclick="resetVotes()">Nouveau vote</button>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <script src="app.js"></script>
</body>
</html>
