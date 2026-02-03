/**
 * Funziona di validazone IBAN italiano
 *
 * Possibile alternativa: ibantools
 *
 * @param iban
 * @returns {boolean}
 */
function validaIbanItaliano(iban) {
  // RIMUOVO SPAZI E CONVERTO IN MAIUSCOLO
  iban = iban.replace(/\s/g, '').toUpperCase();

  // CONTROLLO FORMATO BASE IBAN ITALIANO (IT + 2 CIFRE CHECK + 23 CARATTERI)
  if (!/^IT\d{2}[A-Z]\d{10}[A-Z0-9]{12}$/.test(iban)) {
    return false;
  }

  // ALGORITMO MOD-97 PER VALIDAZIONE CHECKSUM IBAN
  const riorganizzato = iban.slice(4) + iban.slice(0, 4);
  const numerico = riorganizzato.replace(/[A-Z]/g, char =>
      (char.charCodeAt(0) - 55).toString()
  );

  // CALCOLO MODULO 97 SU STRINGHE LUNGHE
  let resto = '';
  for (let i = 0; i < numerico.length; i += 7) {
    resto = (parseInt(resto + numerico.substr(i, 7)) % 97).toString();
  }

  return parseInt(resto) === 1;
}

/**
 * Permette solo numeri in una input text
 *
 * @param evt
 * @param permitExtra
 * @returns {boolean}
 */
function soloNumeri(evt, permitExtra = false) {
  const charCode = evt.keyCode || evt.which;

  if (permitExtra) {
    if ([8, 9, 27, 13, 46, 37, 38, 39, 40].includes(charCode)) {
      return true;
    }
  }

  // NUMERI TASTIERA PRINCIPALE (48-57) O NUMPAD (96-105)
  if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
    return true;
  }

  evt.preventDefault();
  return false;
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

/**
 * Controlla la validità della password seguendo questi criteri:
 * - deve contenere almeno N caratteri (8 default)
 * - almeno un numero
 * - almeno un carattere maiuscolo
 * - almeno un carattere minuscolo
 * - almeno un carattere speciale
 *
 * @param str
 * @param minLength
 * @returns {boolean}
 */
function validatePassword(str, minLength = 8) {
  const hasMinLength = str.length >= minLength;
  const hasNumber = /\d/.test(str);
  const hasUppercase = /[A-Z]/.test(str);
  const hasLowercase = /[a-z]/.test(str);
  const hasSpecial = /[^A-Za-z0-9]/.test(str);

  return hasMinLength && hasNumber && hasUppercase && hasLowercase && hasSpecial;
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
