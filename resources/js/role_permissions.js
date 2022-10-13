export default {

    // Single role check
    checkSingleRole(singleRole) {
        return this.roles.includes(singleRole)
    },

    // Function definiton with passing two arrays
    checkAnyRoleOfArray(array2) {
        if (this.roles) {
            let allRoles = this.roles
            // Loop for array1
            for (let i = 0; i < allRoles.length; i++) {
                // Loop for array2
                for (let j = 0; j < array2.length; j++) {
                    // Compare the element of each and every element from both of the
                    if (allRoles[i] === array2[j]) {
                        // Return if common element found
                        return true;
                    }
                }
            }
        }
        // Return if no common element exist
        return false;
    },

    // All Role permisions

    // Check role by value
    isPermit(val = null) {
        if (val) {
            if (this.checkSingleRole('administrator')) {
                return true;
            } else if (this.checkSingleRole('admin')) {
                return true;
            } else {
                return this.checkSingleRole(val);
            }
        } else {
            return false;
        }
    },

    // Check role by array value
    isAnyPermit(val = []) {
        if (val) {
            if (this.checkSingleRole('administrator')) {
                return true;
            } else if (this.checkSingleRole('admin')) {
                return true;
            } else {
                return this.checkAnyRoleOfArray(val);
            }
        } else {
            return false;
        }
    },


    isAdministrator() {
        return this.checkSingleRole('administrator');
    },
    isAdmin() {
        return this.checkAnyRoleOfArray(['administrator', 'admin']);
    },


}