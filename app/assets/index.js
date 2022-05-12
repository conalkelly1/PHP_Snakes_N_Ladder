const { createApp, ref } = Vue;

const TURN_PLAYER_ONE = 1;
const TURN_PLAYER_TWO = 2;

const gameData = ref(null);
const wormhole = ref(null);
const blackhole = ref(null);
const diceRollValue = ref(null);
const didPlayerBust = ref(null);
const loadGameResponse = ref(null);
const saveGameResponse = ref(null);
const winner = ref(null);
const leaderboard = ref(null);

function setVueRefObjects(res) {
  gameData.value = res.game ?? gameData.value;
  wormhole.value = res.wormholeFoundTile
    ? {
        entry: res.wormholeFoundTile,
        exit: res.wormholeExitTile,
      }
    : null;
  blackhole.value = res.blackholeFoundTile
    ? {
        entry: res.blackholeFoundTile,
        exit: res.blackholeExitTile,
      }
    : null;
  diceRollValue.value = res.diceRollValue ?? null;
  didPlayerBust.value = res.didPlayerBust ?? null;
  loadGameResponse.value = res.loadGameResponse ?? null;
  saveGameResponse.value = res.saveGameResponse ?? null;
  winner.value = res.winner ?? winner.value;
  leaderboard.value = res.leaderboard ?? leaderboard.value;
}

function doActionViaFetch(action, additionalProps) {
  const formData = new FormData();
  formData.append('action', action);
  if (additionalProps) {
    for (const [key, value] of Object.entries(additionalProps)) {
      formData.append(key, value);
    }
  }

  fetch('/main.php', {
    body: formData,
    method: 'post',
  })
    .then((res) => res.json())
    .then(setVueRefObjects);
}

doActionViaFetch('getGame');
doActionViaFetch('getLeaderboard');

createApp({
  data() {
    return {
      TURN_PLAYER_ONE,
      TURN_PLAYER_TWO,
      game: gameData,
      wormhole: wormhole,
      blackhole: blackhole,
      diceRollValue: diceRollValue,
      didPlayerBust: didPlayerBust,
      loadGameResponse: loadGameResponse,
      saveGameResponse: saveGameResponse,
      winner: winner,
      leaderboard: leaderboard,
      loadGameIdInput: null,
      player1Name: null,
      player2Name: null,
    };
  },
  computed: {
    getBoard() {
      return this.game?.board;
    },
    getPlayerOne() {
      this.player1Name = this.game?.playerOne.name;
      return this.game?.playerOne;
    },
    getPlayerTwo() {
      this.player2Name = this.game?.playerTwo.name;
      return this.game?.playerTwo;
    },
    getPlayerOneTileIndex() {
      const player = this.getPlayerOne;
      const playerTileIndex = player.y * 6 + player.x;
      return playerTileIndex;
    },
    getPlayerTwoTileIndex() {
      const player = this.getPlayerTwo;
      const playerTileIndex = player.y * 6 + player.x;
      return playerTileIndex;
    },
  },
  methods: {
    rollDice() {
      doActionViaFetch('roll');
      doActionViaFetch('getLeaderboard');
    },
    saveGame() {
      doActionViaFetch('save');
    },
    loadGame() {
      doActionViaFetch('load', { game_id: this.loadGameIdInput });
    },
    resetGame() {
      doActionViaFetch('reset');
    },
    startGame() {
      console.log('start');
      doActionViaFetch('start', {
        playerOneName: this.player1Name,
        playerTwoName: this.player2Name,
      });
    },
  },
}).mount('#game');
