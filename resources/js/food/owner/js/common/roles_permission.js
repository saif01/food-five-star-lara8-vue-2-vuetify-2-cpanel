export default {

    // Single role check
    checkSingleRole(singleRole) {
        return this.userRoles.includes(singleRole)
    },

    // Function definiton with passing two arrays
    checkAnyRoleOfArray(array2) {
      
        // Loop for array1
        for(let i = 0; i < this.userRoles.length; i++) {
            
            // Loop for array2
            for(let j = 0; j < array2.length; j++) {
                
                // Compare the element of each and
                // every element from both of the
                // arrays
                if(this.userRoles[i] === array2[j]) {
                
                    // Return if common element found
                    return true;
                }
            }
        }
        
        // Return if no common element exist
        return false; 
    },

  





    isAdministrator(){
        return this.checkSingleRole('Administrator');
    },

    // isViewPermitted(){
    //     //console.log(this.userPermission)
    //     return this.checkAnyRoleOfArray(['Administrator', 'View']);
    // },
    // isAddPermitted(){
    //     return this.checkAnyRoleOfArray(['Administrator', 'Add']);
    // },
    // isEditPermitted(){
    //     return this.checkAnyRoleOfArray(['Administrator', 'Edit']);
    // },
    // isDeletePermitted(){
    //     return this.checkAnyRoleOfArray(['Administrator', 'Delete']);
    // },

    
    isAdmin(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin']);
    },
    isAuditor(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Auditor']);
    },
    isMro(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'MRO']);
    },
    isFood(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Food']);
    },
    isReport(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Report']);
    },
    isSchedule(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Schedule']);
    },
    isTemplate(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Template']);
    },
    isVendorList(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'Vendor-list']);
    },
    isUser(){
        return this.checkAnyRoleOfArray(['Administrator', 'Admin', 'User']);
    },
   
}