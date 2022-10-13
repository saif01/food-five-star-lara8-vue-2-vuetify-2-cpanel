import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
    'english': {
        nav__profile: 'Profile',
        nav__editProfile: 'Profile',
        nav__resetPassword: 'Reset Password',
        nav__report: 'Report',
        nav__dailySales: 'Today Sales',
        nav__monthlySales: 'Monthly Sales',
        nav__modifyOrder: 'Modify Order',
        nav__allOrders: 'All Orders',
        nav__stock: 'Stock',
        nav__allSales: 'All Sales',
        nav__allOrder: 'All Order',
        nav__newOrder: 'New Order',
        nav__dashboard: 'Dashboard',
        nav__currentstock: 'Current Stock',
        nav__logout: 'Logout',

        // edit profile
        edit__profile: 'Profile',
        name__: 'Name',
        name__placeholder: 'Enter your name',
        number__: 'Contact Number',
        number__placeholder: 'Enter your phone number',
        address: 'Address',
        address__placeholder: 'Enter your outlet address',
        image__: 'Choose image',
        update__btn: 'Update',
        select__language: 'Select Language',
        outlet__information: 'Outlet Information',


        // reset password
        reset__password: 'Reset Password',
        current__password: 'Current Password',
        currentpass__placeholder: 'Enter current password',
        new__password: 'New Password',
        newpass__placeholder: 'Enter new password',
        update__password: 'Update Password',
        confirm__password: 'Confirm Password',
        confirmpass__placeholder: 'Enter Confirm Password',



        // all order
        order__date: 'Order Date',
        status__: 'Status',
        action__: 'Action',
        approve__: 'Approve',
        waiting__for__approve: 'Waiting for Approve',
        modify__order: 'Modify',
        order__details: 'Details',
        name__: 'Name',
        quantity__: 'Quantity',
        total__: 'Total',
        subtotal__: 'Subtotal',
        payment__: 'Payment',
        make__payment: 'Make Payment',
        payment__completed: 'Payment Completed',
        view__document: 'Document',
        order__number: 'Order Number',
        weight__: 'Weight',
        kg__: 'Kg',
        name__product: 'Product',


        // modify order
        order__place__date: 'Order Place Date',
        order__list: 'Order List',
        delete__order: 'Remove',
        add__to__list: 'Add',
        confirm__order: " Confirm Order",
        place__order: 'Place Order',
        latest__: 'Latest',
        sales__time: 'Sales Time',

        // cart
        product__: 'Product',
        cart__total: 'CART TOTALS',

        // customer
        customer__details: 'Customer Details',
        choose__age: 'Customer Age Range',
        age__1: '14-18',
        age__2: '19-22',
        age__3: '23-30',
        age__4: '28-31',
        age__5: '30+',
        choose__type: 'Customer Gender',
        male__: 'Male',
        female__: 'Female',
        other__: 'Other',
        submit__: 'Submit',
        sales__number: 'Sales Number',
        customer__name: 'Customer Name',
        customer__number: 'Customer Number',
        customer__name__placeholder: 'Enter Customer Name',
        customer__number__placeholder: 'Enter Customer Number',


        // report
        today__sales__report: 'Today Sales Report',
        monthly__sales__report: 'Monthly Sales Report',
        total__sales: 'Total Sale Amount',
        product__name: 'Product Name',

        // extra
        required: 'This field is required',
        price__: 'Price',
        food__type: 'Food Type',
        search__food: 'Search Product...',
        close__: 'Close',
        add__to__cart: 'Add to Cart',
        remove__from__cart: 'Remove from Cart',
        offer__: 'Offer',
        proceed__to__checkout: 'Checkout',
        age__: 'Age',
        type__: 'Type',
        next__: 'Next',
        previous__: 'Previous',
        search__by__any__data: 'Search ...',
        sold__out__of: 'sold out of',
        start__: 'Start Date',
        end__: 'End Date',
        search__: 'Search',
        invoice__: 'Invoice',
        shop__name: 'Outlet Name',
        division__: 'Division',
        district__: 'District',
        city__: 'City',
        shop__address: 'Address',
        sales__date: 'Sales Date',
        place__new__order: 'Place New Order',
        set__: 'Set',
        bdt__: 'BDT',
        rcv__amount: 'Receive Amount',
        enter__rcv__amount: 'Enter Receive Amount',
        created__at: 'Created At',
        updated__at: 'Last Modified At',
        payment__amount: 'Payment Amount',
        enter__payment__amount: 'Enter Payment Amount',
        show__: 'Show',

        // stock
        stock__list: 'All Stock',
        stock__product__name: 'Product Name',
        stock__have: 'In Stock',
        stock__sold: 'Sold',
        stock__remaining: 'Remaining',

    },
    'bangla': {
        nav__profile: 'প্রোফাইল',
        nav__editProfile: 'প্রোফাইল',
        nav__resetPassword: 'রিসেট পাসওয়ার্ড',
        nav__report: 'রিপোর্ট',
        nav__dailySales: 'আজকের বিক্রি',
        nav__monthlySales: 'মাসিক বিক্রি',
        nav__modifyOrder: 'অর্ডার পরিবর্তন',
        nav__allSales: 'সকল বিক্রি',
        nav__stock: 'স্টক',
        nav__allOrder: 'সব অর্ডার',
        nav__newOrder: 'নতুন অর্ডার',
        nav__currentstock: 'বর্তমান স্টক',
        nav__logout: 'লগআউট',


        // edit profile
        edit__profile: 'প্রোফাইল',
        name__: 'নাম',
        name__placeholder: 'নাম লিখুন',
        number__: 'মোবাইল নাম্বার',
        number__placeholder: 'মোবাইল নাম্বার লিখুন',
        address: 'দোকানের ঠিকানা',
        address__placeholder: 'আপনার দোকানের ঠিকানা লিখুন',
        image__: 'ছবি নির্বাচন করুন',
        update__btn: 'পরিবর্তন করুন',
        select__language: 'ভাষা নির্বাচন করুন',
        outlet__information: 'দোকানের তথ্য',


        // reset password
        reset__password: 'পাসওয়ার্ড পরিবর্তন',
        current__password: 'বর্তমান পাসওয়ার্ড',
        currentpass__placeholder: 'বর্তমান পাসওয়ার্ড দিন',
        new__password: 'নতুন পাসওয়ার্ড',
        newpass__placeholder: 'নতুন পাসওয়ার্ড দিন',
        update__password: 'পাসওয়ার্ড পরিবর্তন করুন',
        confirm__password: 'পুনরায় নতুন পাসওয়ার্ড',
        confirmpass__placeholder: 'পুনরায় নতুন পাসওয়ার্ড দিন',

        // all order
        order__date: 'অর্ডার তারিখ',
        status__: 'স্ট্যাটাস',
        action__: 'অ্যাকশন',
        approve__: 'গ্রহণ হয়েছে',
        waiting__for__approve: 'অপেক্ষায় আছে',
        modify__order: 'পরিবর্তন',
        order__details: 'বিস্তারিত',
        name__: 'নাম',
        quantity__: 'পরিমাণ',
        total__: 'মোট',
        subtotal__: 'সর্বমোট',
        payment__: 'পেমেন্ট',
        make__payment: 'পেমেন্ট করুন',
        payment__completed: 'পেমেন্ট হয়েছে',
        view__document: 'ফাইল',
        order__number: 'অর্ডার নাম্বার',
        weight__: 'ওজন',
        kg__: 'কেজি',
        name__product: 'পণ্য',

        // modify order
        order__place__date: 'অর্ডার দেয়ার তারিখ',
        order__list: 'সকল অর্ডার',
        delete__order: 'সরিয়ে ফেলুন',
        add__to__list: 'যোগ করুন',
        confirm__order: 'অর্ডার নিশ্চিত করুন',
        place__order: 'অর্ডার দিন',
        latest__: 'সর্বশেষ',
        sales__time: 'বিক্রির সময়',


        // cart
        product__: 'পণ্য',
        cart__total: 'কার্ট সর্বমোট',

        // customer
        customer__details: 'কাস্টমারের বর্ণনা',
        choose__age: 'কাস্টমারের বয়স সীমা নির্বাচন করুন',
        age__1: '14-18',
        age__2: '19-22',
        age__3: '23-30',
        age__4: '28-31',
        age__5: '30+',
        choose__type: 'কাস্টমারের লিঙ্গ নির্বাচন করুন',
        male__: 'পুরুষ',
        female__: 'মহিলা',
        other__: 'অন্যান্য',
        submit__: 'জমা দিন',
        sales__number: 'সেলস নাম্বার',
        customer__name: 'কাস্টমারের নাম',
        customer__number: 'কাস্টমারের নাম্বার',
        customer__name__placeholder: 'কাস্টমারের নাম লিখুন',
        customer__number__placeholder: 'কাস্টমারের নাম্বার লিখুন',


        // report
        today__sales__report: 'আজকের বিক্রি রিপোর্ট',
        monthly__sales__report: 'মাসিক বিক্রি রিপোর্ট',
        total__sales: 'সর্বমোট বিক্রি',
        product__name: 'পণ্যের নাম',


        // extra
        required: 'ঘরটি অবশ্যই পূরণ করতে হবে',
        price__: 'মূল্য',
        food__type: 'খাবারের ধরণ',
        search__food: 'পণ্য খুঁজুন',
        close__: 'বন্ধ',
        add__to__cart: 'কার্টে যোগ করুন',
        remove__from__cart: 'বাতিল করুন',
        nav__dashboard: 'ড্যাশবোর্ড',
        offer__: ' ছাড় ',
        proceed__to__checkout: 'চেকআউট',
        age__: 'বয়স',
        type__: 'ধরণ',
        next__: 'পরবর্তী',
        previous__: 'পূর্ববর্তী',
        search__by__any__data: 'যেকোনো তথ্য দ্বারা অনুসন্ধান করুন',
        sold__out__of__1: 'টির ভিতর',
        sold__out__of__2: 'টি বিক্রি হয়েছে',
        start__: 'শুরু',
        end__: 'শেষ',
        search__: 'অনুসন্ধান',
        invoice__: 'বিল',
        shop__name: 'দোকানের নাম',
        division__: 'বিভাগ',
        district__: 'জেলা',
        city__: 'শহর',
        shop__address: 'দোকানের ঠিকানা',
        sales__date: 'বিক্রয়ের তারিখ',
        place__new__order: 'নতুন অর্ডার দিন',
        set__: 'প্যাকেট',
        bdt__: 'টাকা',
        rcv__amount: 'টাকার পরিমাণ',
        enter__rcv__amount: 'টাকার পরিমাণ দিন',
        created__at: 'তৈরির তারিখ',
        updated__at: 'শেষ পরিবর্তনের তারিখ',
        payment__amount: 'টাকার পরিমাণ',
        enter__payment__amount: 'টাকার পরিমাণ দিন',
        show__: 'দেখুন',


        // stock
        stock__list: 'মজুত তালিকা',
        stock__product__name: 'পণ্যের নাম',
        stock__have: 'মজুত আছে',
        stock__sold: 'বিক্রি হয়েছে',
        stock__remaining: 'অবশিষ্ট আছে',

    }

};

const i18n = new VueI18n({
    locale: 'english', // set locale
    fallbackLocale: 'bangla', // set fallback locale
    messages, // set locale messages
});


export default new VueI18n({
    locale: 'english',
    fallbackLocale: 'bangla',
    messages,
})