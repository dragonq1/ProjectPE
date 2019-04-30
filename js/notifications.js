//Stijl aanmaken
$(document).ready(function() {
  notifiStyles();
})
//stijl voor notificaties
function notifiStyles() {
  $.notify.addStyle('success', {
  html: "<div>✔ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#2e7d32",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });
  $.notify.addStyle('error', {
  html: "<div>✕ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#c63f17",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });

  $.notify.addStyle('warning', {
  html: "<div>✕ <span data-notify-text/></div>",
  classes: {
    base: {
      "background-color": "#f9a825",
      "padding": "10px",
      "color": "white",
      "border-radius": "3px"
    }
  }
  });
}

function notify(returnCode) {
  switch(returnCode) {
    case 201:
      $.notify("Een account met dat e-mail adres bestaat al!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 202:
      $.notify("Een account met die nickname adres bestaat al!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 203:
      $.notify("Wachtwoorden komen niet overeen!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 204:
      $.notify("Registratie succesvol, bevestig uw account via e-mail!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 205:
      $.notify("Deze registratie token is niet geldig!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 206:
      $.notify("Uw account is bevestigd! U kan nu inloggen!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 207:
      $.notify("Er ging iets fout! #207", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 301:
      $.notify("Wachtwoorden zijn niet hetzelfde!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 302:
      $.notify("Er ging iets fout! #302", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 303:
      $.notify("Er ging iets fout! #303", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 304:
      $.notify("De reset e-mail werd verstuurd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 350:
      $.notify("Incorrecte inlog gegevens!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 351:
      $.notify("Incorrecte inlog gegevens!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 352:
      $.notify("Account is nog niet geverifeerd! Kijk uw e-mail om uw account te bevestigen.", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 401:
      $.notify("Er ging iets fout! #401", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 402:
      $.notify("Er ging iets fout! #402", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 450:
      $.notify("Er ging iets fout bij het ophalen van account gegevens! #450", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 451:
      $.notify("Het oude wachtwoord is niet correct!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
      case 452:
        $.notify("Wachtwoord herhalen is niet correct!", {
          style: "warning",
          autoHide: true,
          clickToHide: true
        });
        break;
      case 453:
        $.notify("Wachtwoord veranderen gelukt!", {
          style: "success",
          autoHide: true,
          clickToHide: true
        });
        break;
    case 501:
      $.notify("Er ging iets fout! #501", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 502:
      $.notify("Er ging iets fout! #502", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 503:
      $.notify("Uitnodiging geaccepteerd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 504:
      $.notify("Je kan geen uitnodiging naar jezelf sturen!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 505:
      $.notify("Deze gebruiker zit al in deze groep!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 506:
      $.notify("Deze gebruiker heeft al uitnodiging!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 507:
      $.notify("Deze gebruiker bestaat niet!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 508:
      $.notify("Uitnodiging verstuurd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 509:
      $.notify("Uitnodiging geweigerd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 601:
      $.notify("Er ging iets fout! #601", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 650:
      $.notify("Fout bij versturen van bericht!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 700:
      $.notify("Je hebt niet genoeg rechten!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 701:
      $.notify("Vul alle velden in!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 702:
      $.notify("Er ging iets fout! #702", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 703:
      $.notify("Wachtwoord herhalen is niet correct!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 801:
      $.notify("Er ging iets fout! #801", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 802:
      $.notify("Er ging iets fout! #802", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 803:
      $.notify("Een bestand met dezelfde naam bestaat al!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 804:
      $.notify("Het bestand is te groot. De max. grote is 20MB!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 805:
      $.notify("Het bestand is geüpload!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 806:
      $.notify("Het bestand werd verwijderd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 901:
      $.notify("Deze gebruiker bestaat niet of zit niet in de groep!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 902:
      $.notify("Je kan jezelf niet verwijderen uit de groep!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 903:
      $.notify("Fout bij ophalen van uw rechten!", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 904:
      $.notify("Je kan de groep niet verlaten als eigenaar!", {
        style: "warning",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 905:
      $.notify("Er ging iets fout! #905", {
        style: "error",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 906:
      $.notify("Je hebt de groep verlaten.", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 907:
      $.notify("Gebruiker is verwijderd uit de groep!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 908:
      $.notify("Groep verwijderd!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 909:
      $.notify("Vak aangemaakt!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    case 910:
      $.notify("Groep aangemaakt!", {
        style: "success",
        autoHide: true,
        clickToHide: true
      });
      break;
    // TODO:
    //VERANDEREN NAAR STANDAARD BERICHT BIJ RELEASE -> ERROR GAAN ANDERS DOOR NAAR FRONTEND
    default:
      alert(returnCode);
      break;
  }

}
