<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Planning Poker - Accueil</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="wrap">
    <h1>Planning Poker (MVP)</h1>

    <section class="card">
      <h2>Créer une salle</h2>
      <form action="api/createRoom.php" method="POST" id="createForm">
        <label for="createUser">Votre pseudo :</label>
        <input type="text" id="createUser" name="user" required placeholder="Ex: Alice" />
        <label for="createPwd">Mot de passe (optionnel) :</label>
        <input type="password" id="createPwd" name="password" placeholder="Laisser vide pour pas de mot de passe" />
        <button type="submit">Créer la salle</button>
      </form>
    </section>

    <section class="card">
      <h2>Rejoindre une salle</h2>
      <form action="salle.php" method="GET" id="joinForm">
        <label for="roomId">Code salle :</label>
        <input type="text" id="roomId" name="id" required placeholder="ex: a1b2c3/123456" />
        <label for="joinUser">Votre pseudo :</label>
        <input type="text" id="joinUser" name="user" required placeholder="Ex: Said" />
        <label for="joinPwd">Mot de passe (si requis) :</label>
        <input type="password" id="joinPwd" name="password" placeholder="Mot de passe de la salle" />
        <button type="submit">Entrer dans la salle</button>
      </form>
    </section>

    <footer style="margin-top:20px;font-size:0.9rem;color:#666;">
      Version MVP — PHP + JSON 
    </footer>
  </main>
</body>
</html>
