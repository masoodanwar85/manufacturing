var digit_0 = "صفر";
var digit_1 = "ایک";
var digit_2 = "دو";
var digit_3 = "تین";
var digit_4 = "چار";
var digit_5 = "پانچ";
var digit_6 = "چھ";
var digit_7 = "سات";
var digit_8 = "آٹھ";
var digit_9 = "نو";
var digit_10 = "دس";
var digit_11 = "گیارہ";
var digit_12 = "بارہ";
var digit_13 = "تیرہ";
var digit_14 = "چودہ";
var digit_15 = "پندرہ";
var digit_16 = "سولہ";
var digit_17 = "سترہ";
var digit_18 = "اٹھارہ";
var digit_19 = "انیس";
var digit_20 = "بیس";
var digit_21 = "اکیس";
var digit_22 = "بائیس";
var digit_23 = "تئیس";
var digit_24 = "چوبیس";
var digit_25 = "پچیس";
var digit_26 = "چھببیس";
var digit_27 = "ستائیس";
var digit_28 = "اٹھائیس";
var digit_29 = "انتیس";
var digit_30 = "تیس";
var digit_31 = "اکتیس";
var digit_32 = "بتیس";
var digit_33 = "تینتیس";
var digit_34 = "چوتیس";
var digit_35 = "پینتیس";
var digit_36 = "چھتیس";
var digit_37 = "سنتیس";
var digit_38 = "اڑتیس";
var digit_39 = "انتالیس";
var digit_40 = "چالیس";
var digit_41 = "اکتالیس";
var digit_42 = "بیالیس";
var digit_43 = "تینتالیس / ترالیس";
var digit_44 = "چوالیس";
var digit_45 = "پینتالیس";
var digit_46 = "چھیالیس";
var digit_47 = "سینتالیس";
var digit_48 = "  اڑتالیس";
var digit_49 = "انچاس";
var digit_50 = "پچاس";
var digit_51 = "اکاون";
var digit_52 = "باون";
var digit_53 = "تریپن";
var digit_54 = "چون";
var digit_55 = "پچپن";
var digit_56 = "چھپن";
var digit_57 = "ستاون";
var digit_58 = "اٹھاون";
var digit_59 = "انسٹھ";
var digit_60 = "ساٹھ";
var digit_61 = "اکسٹھ";
var digit_62 = "باسٹھ";
var digit_63 = "تریسٹھ";
var digit_64 = "چونسٹھ";
var digit_65 = "پینسٹھ";
var digit_66 = "چھیاسٹھ";
var digit_67 = "سڑسٹھ";
var digit_68 = "اڑسٹھ";
var digit_69 = "انهتر";
var digit_70 = "ستر";
var digit_71 = "اکھتر";
var digit_72 = "بہتر";
var digit_73 = "تہتر";
var digit_74 = "چوہتر";
var digit_75 = "پچھتر";
var digit_76 = "چھہتر";
var digit_77 = "ستتر";
var digit_78 = "اٹھتر";
var digit_79 = "اناسی";
var digit_80 = "اسی";
var digit_81 = "اکاسی";
var digit_82 = "بیاسی";
var digit_83 = "تراسی";
var digit_84 = "چوراسی";
var digit_85 = "پچھاسی";
var digit_86 = "چھیاسی";
var digit_87 = "ستاسی";
var digit_88 = "اٹھاسی";
var digit_89 = "نواسی";
var digit_90 = "نوے";
var digit_91 = "اکانوے";
var digit_92 = "بانوے";
var digit_93 = "ترانوے";
var digit_94 = "چورانوے";
var digit_95 = "پچانوے";
var digit_96 = "چھیانوے";
var digit_97 = "ستانوے";
var digit_98 = "اٹھانوے";
var digit_99 = "ننانوے";
var digit_100 = "سو";
var digit_1000 = "ہزار";
var digit_10000 = "دس ہزار";
var digit_100000 = "لاکھ";
var digit_1000000 = "دس لاکھ";
var digit_10000000 = "کروڑ";
var digit_100000000 = "دس کروڑ";
var digit_1000000000 = "ارب";
var digit_10000000000 = "دس ارب";
var digit_100000000000 = "کھرب";
var digit_1000000000000 = " دس کھرب";

function translate(number) {
    var stRangeIndex = {
        'length_3'  : { 'range' : 100, 'index' : 1},
        'length_4'  : { 'range' : 1000, 'index' : 1},
        'length_5'  : { 'range' : 1000, 'index' : 2},
        'length_6'  : { 'range' : 100000, 'index' : 1},
        'length_7'  : { 'range' : 100000, 'index' : 2},
        'length_8'  : { 'range' : 10000000, 'index' : 1},
        'length_9'  : { 'range' : 10000000, 'index' : 2},
        'length_10' : {  'range' : 1000000000, 'index' : 1},
        'length_11' : {  'range' : 1000000000, 'index' : 2},
        'length_12' : {  'range' : 100000000000, 'index' : 1},
        'length_13' : {  'range' : 100000000000, 'index' : 2}
    };
    var translated = '';
    var number_to_convert = number;
    while (number_to_convert != 0) {
        var length = number_to_convert.toString().length;
        var constant_name = `digit_${number_to_convert}`;
        if (window[constant_name]) {
            translated += window[constant_name];
            break;
        } else {
            var aryCurrentRangeIndex = stRangeIndex[`length_${length}`];
            var range = aryCurrentRangeIndex['range'];
            var idxPoint = aryCurrentRangeIndex['index'];
            var first_number = number_to_convert.toString().substring(0,idxPoint);
            var remaining_numbers = number_to_convert.toString().substring(idxPoint,number_to_convert.toString().length);

            translated += window[`digit_${first_number}`] + ' ' + window[`digit_${range}`] + ' ';

            number_to_convert = parseInt(remaining_numbers);
        }
    }
    return translated;
}
