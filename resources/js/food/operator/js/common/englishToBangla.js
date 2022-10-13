export default {
    englishTobangla(number) {
        var bangla_converted_number = null;
        if (number) {
                var finalEnlishToBanglaNumber = {
                '0': '০',
                '1': '১',
                '2': '২',
                '3': '৩',
                '4': '৪',
                '5': '৫',
                '6': '৬',
                '7': '৭',
                '8': '৮',
                '9': '৯'
            };

            String.prototype.getDigitBanglaFromEnglish = function () {
                var retStr = this;
                for (var x in finalEnlishToBanglaNumber) {
                    retStr = retStr.replace(new RegExp(x, 'g'), finalEnlishToBanglaNumber[x]);
                }
                return retStr;
            };

            var english_number = number.toString();

            var bangla_converted_number = english_number.getDigitBanglaFromEnglish();
        }
        

        return bangla_converted_number;
    },




    // calendarToBangla(d) {

        
        
        
    //     if (d) {
    //         var monthReplace = {

    //             'january': 'জানুয়ারী',
    //             'february': 'ফেব্রুয়ারী',
    //             'march': 'মার্চ',
    //             'april': 'এপ্রিল',
    //             'may': 'মে',
    //             'june': 'জুন',
    //             'july': 'জুলাই',
    //             'august': 'আগস্ট',
    //             'september': 'সেপ্টেম্বর',
    //             'october': 'অক্টোবর',
    //             'november': 'নভেম্বর',
    //             'december': 'ডিসেম্বর',
 
    //         }


    //         var weekReplace = {

    //             'saturday': 'শনিবার',
    //             'sunday': 'রবিবার',
    //             'monday': 'সোমবার',
    //             'tuesday': 'মঙ্গলবার',
    //             'wednesday': 'বুধবার',
    //             'thursday': 'বৃহস্পতিবার',
    //             'friday': 'শুক্রবার',

    //         }


    //         // sepearete date string
    //         var test = 'Monday, August 22nd, 9:54 am';


    //         String.prototype.changeEnglishToBanglaDate = function () {
    //             var retStr = this;
    //             for (var x in monthReplace) {
    //                 retStr = retStr.replace(new RegExp(x, 'g'), monthReplace[x]);
    //             }
    //             return retStr;
    //         };



    //         var bangla_converted_date = test.toLowerCase().toString().getDigitBanglaFromEnglish();

    //         console.log(bangla_converted_date);


    //     }
    // }


}
