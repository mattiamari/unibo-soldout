'use strict';

const dummyUser = {
    fistname: 'Pinco',
    lastname: 'Pallino',
    email: 'pinco@pallino.com',
    birthdate: '1980-03-20'
};

const Account = {
    isLoggedIn() {
        return true;
    },

    getUser() {
        return dummyUser;
    }
};

export default Account;
