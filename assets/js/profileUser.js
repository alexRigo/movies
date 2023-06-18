import '../styles/profileUser.scss'

const profileUser ={

    init: () =>{
        profileUser.userSection();
        profileUser.editProfile();
    },

    userSection: () =>{

        let buttons = document.querySelectorAll('.button');
        let sections = document.querySelectorAll('.section');

        function removeActive() {
            buttons.forEach(button => {
                button.classList.remove('active')
            })
            sections.forEach(section => {
                section.classList.remove('active')
            })
        }
        buttons.forEach(button =>  {
            button.addEventListener('click', () => {
                removeActive()
                button.classList.add('active')
                sections.forEach(section => {
                    if (button.classList.contains(section.id)){
                        section.classList.add('active')
                    }
                });
            
            })
        });
    },

    editProfile: () => {

       const showFormButton = document.querySelector(".edit-profile-button");
       const userInfos = document.querySelector(".profile-user-infos");
       const editProfile = document.querySelector(".edit-profile");
       const cancel = document.querySelector(".cancel-edit-profile");

        showFormButton.addEventListener('click', () => {
             editProfile.style.display = "block" 
            userInfos.style.display = "none"
      })
        cancel.addEventListener('click', () => {
            editProfile.style.display = "none"
            userInfos.style.display = "block"
  })
      
    }


}


profileUser.init();