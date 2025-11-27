let roomId = null, userName = null, isAdmin = false;
let myVoteValue = null;
let pollInterval = null;
let roomPassword = ''; // stocke temporairement le mot de passe (session navigateur)
const CARD_VALUES = ['1','2','3','5','8','13','21','?','☕'];

function initRoom(id, user, admin, pwd) {
  roomId = id;
  userName = user;
  isAdmin = admin;
  roomPassword = pwd || '';

  renderCards();
  joinRoom();

  // polling toutes les 1.5s
  updateState();
  pollInterval = setInterval(updateState, 1500);
}

function renderCards(){
  const container = document.getElementById('cards');
  container.innerHTML = '';
  CARD_VALUES.forEach(v=>{
    const btn = document.createElement('button');
    btn.innerText = v;
    btn.onclick = () => {
      myVoteValue = v;
      document.getElementById('myVote').innerText = v;
      vote(v);
      Array.from(container.querySelectorAll('button')).forEach(b=>b.classList.remove('selected'));
      btn.classList.add('selected');
    };
    container.appendChild(btn);
  });
}

function buildUrl(path, params = {}) {
  params.id = roomId;
  if (roomPassword) params.password = roomPassword;
  return path + '?' + new URLSearchParams(params).toString();
}

function joinRoom(){
  // envoie password si présent
  fetch(buildUrl('api/joinRoom.php', { user: userName }))
    .then(r => r.json())
    .then(j => {
      if (j.error) {
        alert('Erreur join : ' + j.error);
      }
    })
    .catch(e=>console.error('join error',e));
}

function vote(value){
  fetch(buildUrl('api/vote.php', { user: userName, vote: value }))
    .then(r => r.json())
    .then(j => {
      if (j.error) console.error('vote err', j);
    })
    .catch(e=>console.error('vote error',e));
}

function reveal(){
  fetch(buildUrl('api/reveal.php'))
    .then(r => r.json())
    .then(j => {
      if (j.error) alert('Erreur reveal: ' + j.error);
      updateState();
    })
    .catch(e=>console.error('reveal error',e));
}

function resetVotes(){
  fetch(buildUrl('api/reset.php'))
    .then(r => r.json())
    .then(j => {
      if (j.error) alert('Erreur reset: ' + j.error);
      myVoteValue = null;
      document.getElementById('myVote').innerText = '—';
      updateState();
    })
    .catch(e=>console.error('reset error',e));
}

function setStory(){
  const val = document.getElementById('storyInput').value || '';
  fetch(buildUrl('api/setStory.php', { story: val }))
    .then(r => r.json())
    .then(j => {
      if (j.error) alert('Erreur story: ' + j.error);
      updateState();
    })
    .catch(e=>console.error('story error',e));
}

function updateState(){
  fetch(`api/getState.php?id=${encodeURIComponent(roomId)}`)
    .then(r=>r.json())
    .then(data=>{
      if (data.error) {
        console.error('getState error', data.error);
        return;
      }
      document.getElementById('currentStory').innerText = data.story ? data.story : 'Aucune story définie.';
      const partDiv = document.getElementById('participants');
      partDiv.innerHTML = '<h3>Participants</h3>';
      data.users.forEach(u=>{
        const p = document.createElement('div');
        p.className = 'participant';
        p.innerText = u;
        partDiv.appendChild(p);
      });

      const resDiv = document.getElementById('results');
      resDiv.innerHTML = '<h3>Votes</h3>';

      if (!data.revealed) {
        for (const u of data.users) {
          const p = document.createElement('p');
          p.innerText = `${u} : ${u === userName && myVoteValue ? myVoteValue : '🟦'}`;
          resDiv.appendChild(p);
        }
      } else {
        for (const [u,v] of Object.entries(data.votes || {})) {
          const p = document.createElement('p');
          p.innerText = `${u} : ${v}`;
          resDiv.appendChild(p);
        }
        const stats = document.createElement('div');
        stats.style.marginTop = '8px';
        stats.innerHTML = `<strong>Min :</strong> ${data.stats.min ?? '—'} &nbsp; <strong>Max :</strong> ${data.stats.max ?? '—'} &nbsp; <strong>Moy :</strong> ${data.stats.avg ?? '—'}`;
        resDiv.appendChild(stats);
      }
    })
    .catch(e=>console.error('state error',e));
}
