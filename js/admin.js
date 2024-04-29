console.log("aaaaaaaaaaaaaaaaaaaa");

const poleOdkazu = document.querySelectorAll(".odkaz-mazani");
for (let odkaz of poleOdkazu) {
    odkaz.addEventListener("click", (udalost) => {
        udalost.preventDefault();

        const odpoved = confirm("Opravdu chcete sstranku smazat?");
        if (odpoved == true) {
            window.location.href = odkaz.getAttribute("href");
        }
    });
}

//razeni stranek
const elmUl = document.querySelector("#ul-stranek");
$(elmUl).sortable({
    //tato funkce se spusti pri jakekoliv poradi v seznamu
    update: () => {
        //jake je nove poradi stranek
        const poleId = $(elmUl).sortable("toArray");
        console.log(poleId);

        //posleme do serveru pole id
        //ajax
        $.ajax({
            type: "POST",
            url: "./admin.php",
            data: {
                poleSerazenychId: poleId,
                razeniSubmit: true
            },
            dataType: "text", //posledni cast odsud neni potrebna
            success: function (response) {
                console.log(response)
            }
        });
    }
});