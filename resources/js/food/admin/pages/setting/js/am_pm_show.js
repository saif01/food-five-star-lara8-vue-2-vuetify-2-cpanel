export default {
     showAMPM(val) {
      let showTime = null;
      switch (val) {
        case "1:00":
          showTime = "1 AM";
          break;
        case "2:00":
          showTime = "2 AM";
          break;
        case "3:00":
          showTime = "3 AM";
          break;
        case "4:00":
          showTime = "4 AM";
          break;
        case "5:00":
          showTime = "5 AM";
          break;
        case "6:00":
          showTime = "6 AM";
          break;
        case "7:00":
          showTime = "7 AM";
          break;
        case "8:00":
          showTime = "8 AM";
          break;
        case "9:00":
          showTime = "9 AM";
          break;
        case "10:00":
          showTime = "10 AM";
          break;
        case "11:00":
          showTime = "11 AM";
          break;

        case "13:00":
          showTime = "1 PM";
          break;
        case "14:00":
          showTime = "2 PM";
          break;
        case "15:00":
          showTime = "3 PM";
          break;
        case "16:00":
          showTime = "4 PM";
          break;
        case "17:00":
          showTime = "5 PM";
          break;
        case "18:00":
          showTime = "6 PM";
          break;
        case "19:00":
          showTime = "7 PM";
          break;
        case "20:00":
          showTime = "8 PM";
          break;
        case "21:00":
          showTime = "9 PM";
          break;
        case "22:00":
          showTime = "10 PM";
          break;
        case "23:00":
          showTime = "11 PM";
          break;
        case "24:00":
          showTime = "12 PM";
          break;
        default:
          showTime = val;
      }
      return showTime;
    }
}