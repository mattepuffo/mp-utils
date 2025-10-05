function soloNumeri(evt) {
  const charCode = (evt.which) ? evt.which : event.keyCode;
  return !(charCode > 31 && (charCode < 48 || charCode > 57));
}

function checkChar(evt) {
  const permessi = Array();
  const alphabet = 'abcdefghijklmnopqrstuvwxyz';

  for (let i = 0; i < alphabet.length; i++) {
    permessi.push(alphabet.charAt(i));
    permessi.push(alphabet.charAt(i).toUpperCase());
  }

  permessi.push(' ', 'é', 'è', 'ò', 'ù', 'à', 'ì', 'Backspace', 'ArrowRight', 'ArrowLeft', 'Delete', '\'', 'Tab');
  return permessi.includes(evt.key);
}

function validazioneData(data) {
  const regex = /^\d{4}-\d{2}-\d{2}$/;
  return regex.test(data);
}

function validazioneFormatoTempo(tempo) {
  const regex = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/;
  return regex.test(tempo);
}

function validazioneLettereNumeriUnderscore(value) {
  const regex = /^\w+$/;
  return regex.test(value);
}

function validazioneOttoCharsUpperLowerNumero(password) {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  return regex.test(password);
}

function validazioneEmail(email) {
  const regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+.[a-z]{2,4}$/;
  return regex.test(email);
}

function detectMobile() {
  if (navigator.userAgent.match(/Android/i)
      || navigator.userAgent.match(/webOS/i)
      || navigator.userAgent.match(/iPhone/i)
      || navigator.userAgent.match(/iPad/i)
      || navigator.userAgent.match(/iPod/i)
      || navigator.userAgent.match(/BlackBerry/i)
      || navigator.userAgent.match(/Windows Phone/i)
  ) {
    return true;
  } else {
    return false;
  }
}

const isMobile = {
  Android: function () {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: function () {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: function () {
    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: function () {
    return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: function () {
    return navigator.userAgent.match(/IEMobile/i);
  },
  any: function () {
    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};

function mostradata() {
  const data = new Date();
  const mese = data.getMonth() + 1;
  const giorno = data.getDate();
  const anno = data.getYear();
  const annoCorretto = fissaAnno(anno);
  const ora = data.getHours();
  let minuti = data.getMinutes();
  minuti = fissaTempo(minuti);
  return giorno + "-" + mese + "-" + annoCorretto + " " + ora + ":" + minuti;
}

function fissaAnno(annoCorretto) {
  if (annoCorretto < 1000) {
    annoCorretto = annoCorretto + 1900;
  }
  return annoCorretto;
}

function fissaTempo(number) {
  if (number < 10) {
    number = "0" + number;
  }
  return number;
}
