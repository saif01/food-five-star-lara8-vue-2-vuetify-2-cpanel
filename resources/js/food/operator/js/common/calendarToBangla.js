export default {
     calendarToBangla(d, ft) {
      if (d && ft) {
        var monthReplace = {
          january: "জানুয়ারী",
          february: "ফেব্রুয়ারী",
          march: "মার্চ",
          april: "এপ্রিল",
          may: "মে",
          june: "জুন",
          july: "জুলাই",
          august: "আগস্ট",
          september: "সেপ্টেম্বর",
          october: "অক্টোবর",
          november: "নভেম্বর",
          december: "ডিসেম্বর",
        };

        var weekReplace = {
          saturday: "শনিবার",
          sunday: "রবিবার",
          monday: "সোমবার",
          tuesday: "মঙ্গলবার",
          wednesday: "বুধবার",
          thursday: "বৃহস্পতিবার",
          friday: "শুক্রবার",
        };

        var gt = this.$moment(d).format(ft);

        var dateArray = gt
          .toLowerCase()
          .toString()
          .replace(/[^a-zA-Z1-9: ]/g, "")
          .split(" ");

        var date = [];

        for (let index = 0; index < dateArray.length; index++) {
          if (dateArray[index] in weekReplace) {
            var week = dateArray[index].replace(
              new RegExp(dateArray[index], "g"),
              weekReplace[dateArray[index]]
            );
          } else if (dateArray[index] in monthReplace) {
            var month = dateArray[index].replace(
              new RegExp(dateArray[index], "g"),
              monthReplace[dateArray[index]]
            );
          } else {
            date.push(this.englishTobangla(dateArray[index]));
          }
        }

        if (typeof week == 'undefined') {
          week = '';
        } 
        if (typeof month == 'undefined') {
          month = '';
        }

        return week +' '+ month + ' ' + date.toString().replace(',', ' ');
      }
    },
}