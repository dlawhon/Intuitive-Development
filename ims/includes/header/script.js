const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".bx-search"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text"),
      userProfile = body.querySelector(".userProfile");


toggle.addEventListener("click" , () =>{
    sidebar.classList.toggle("close");
})

searchBtn.addEventListener("click" , () =>{
    sidebar.classList.remove("close");
})

searchBtn.addEventListener("click" , () =>{
    window.location.href = "<?= BASE_URL ?>administration/account.php?id=<?=$_SESSION['user_id']?>";
})

// document.getElementById("id_of_textbox")
//     .addEventListener("keyup", function(event) {
//     event.preventDefault();
//     if (event.keyCode === 13) {
//         document.getElementById("id_of_button").click();
//     }
// })
