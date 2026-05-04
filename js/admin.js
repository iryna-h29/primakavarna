$( "#stranky" ).sortable({
    update: () => {
        const sortedIDs = $( "#stranky" ).sortable( "toArray" );
        // console.log(sortedIDs);
        $.ajax({
            url: "admin.php",
            // method: "POST", // pokud nam treba method POST
            data: {
                "poradi": sortedIDs,
            }
        })
    }
});

$("#stranky .smazat").click((event) => {
    if (confirm("Opravdu chcete dannou stránku smazat?") === false) {
        // prerusime udalost cimz se zrusi nasledne navstiveni odkazu
        // pro smazani stranky
        event.preventDefault();
    }
});