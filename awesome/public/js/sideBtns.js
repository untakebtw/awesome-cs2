// Initialize global objects to prevent errors before fetch completes
window.skinsObject = []
window.defaultsObject = []
window.agentsObject = []
window.musicObject = []

const initData = async () => {
    let skinsTemp = await fetch("/js/json/skins/skins.json")
    let defaultsTemp = await fetch("/js/json/defaults/" + (window.lang || 'en') + "-defaults.json")
    let agentsTemp = await fetch("/js/json/skins/agents.json")
    let musicTemp = await fetch("/js/json/skins/music_kits.json")

    window.skinsObject = await skinsTemp.json()
    window.defaultsObject = await defaultsTemp.json()
    window.agentsObject = await agentsTemp.json()
    window.musicObject = await musicTemp.json()

    // Weapons available for both teams (T and CT)
    const bothTeamsWeapons = [
        'weapon_deagle', 'weapon_elite', 'weapon_p250', 'weapon_cz75a', 'weapon_revolver',
        'weapon_bizon', 'weapon_p90', 'weapon_ump45', 'weapon_mp5sd', 'weapon_mp7',
        'weapon_nova', 'weapon_xm1014', 'weapon_m249', 'weapon_negev',
        'weapon_awp', 'weapon_ssg08',
        'weapon_mag7', 'weapon_sawedoff'
    ]
    window.defaultsObject.forEach(w => {
        if (bothTeamsWeapons.includes(w.weapon_name)) {
            w.both_teams = true
        }
        // Move Zeus to Pistols
        if (w.weapon_name === 'weapon_taser') {
            w.weapon_type = 'csgo_inventory_weapon_category_pistols'
        }
    })
};
initData();

const sideBtnHandler = (activeBtn) => {
    // Remove active background
    let allBtns = [
        "sideBtnKnives",
        "sideBtnGloves",
        "sideBtnRifles",
        "sideBtnPistols",
        "sideBtnSmgs",
        "sideBtnShotguns",
        "sideBtnUtility",
        "sideBtnCTAgents",
        "sideBtnTAgents",
        "sideBtnMusic"
    ]

    allBtns.forEach(element => {
        let elms = document.querySelectorAll(`[id="${element}"]`);

        for (var i = 0; i < elms.length; i++)
            elms[i].classList.remove("active-side")
    });

    // Add active background
    let elms = document.querySelectorAll(`[id="${activeBtn}"]`);

    for (var i = 0; i < elms.length; i++)
        elms[i].classList.add("active-side")

    // Hide Back buttons when switching main categories
    const backBtn = document.getElementById("btnBackSidebar");
    const backBtnMobile = document.getElementById("mobileBackContainer");
    if (backBtn) backBtn.style.display = "none";
    if (backBtnMobile) backBtnMobile.style.display = "none";

    window.scrollTo(0, 0)
}

window.goBackCategory = () => {
    const activeSide = document.querySelector(".sideBtn.active-side, .mobile-nav-item.active-side");
    if (activeSide) {
        const btnId = activeSide.id;
        switch (btnId) {
            case "sideBtnKnives": showKnives(); break;
            case "sideBtnGloves": showGloves(); break;
            case "sideBtnRifles": showRifles(); break;
            case "sideBtnPistols": showPistols(); break;
            case "sideBtnSmgs": showSmgs(); break;
            case "sideBtnShotguns": showShotguns(); break;
            case "sideBtnUtility": showUtility(); break;
            default: activeSide.click();
        }
    } else {
        window.location.href = '/';
    }
};

const showDefaults = (type) => {
    document.getElementById("skinsContainer").innerHTML = ""

    if (type == "sfui_invpanel_filter_melee") {
        defaultsObject.forEach(knife => {
            if (knife.weapon_type == "sfui_invpanel_filter_melee") {
                const skinWeapon = selectedSkins.find(element => {
                    if (element.weapon_defindex == weaponIds[knife.weapon_name]) {
                        return true
                    }
                    return false
                })

                if (typeof skinWeapon != "undefined") {
                    changeKnifeSkinTemplate(knife, langObject)
                    changeSkinCard(knife, skinWeapon)
                } else {
                    knivesTemplate(knife, langObject)
                }

            }
        })
    } else if (type == "sfui_invpanel_filter_gloves") {
        defaultsObject.forEach(glove => {
            if (glove.weapon_type == "sfui_invpanel_filter_gloves") {
                const skinWeapon = selectedSkins.find(element => {
                    if (element.weapon_defindex == weaponIds[glove.weapon_name]) {
                        return true
                    }
                    return false
                })

                if (typeof skinWeapon != "undefined") {
                    changeGlovesSkinTemplate(glove, langObject)
                    changeSkinCard(glove, skinWeapon)
                } else {
                    glovesTemplate(glove, langObject)
                }
            }
        })
    } else {
        defaultsObject.forEach(weapon => {
            if (weapon.weapon_type == type) {
                const skinWeapon = selectedSkins.find(element => {
                    if (element.weapon_defindex == weaponIds[weapon.weapon_name]) {
                        return true
                    }
                    return false
                })

                if (typeof skinWeapon != "undefined") {
                    changeSkinTemplate(weapon, langObject)
                    changeSkinCard(weapon, skinWeapon)
                } else {
                    defaultsTemplate(weapon, langObject, lang)
                }
            }
        })
    }
}

const showKnives = () => {
    sideBtnHandler("sideBtnKnives")
    showDefaults("sfui_invpanel_filter_melee")
    getTeamKnives()
}

const showGloves = () => {
    sideBtnHandler("sideBtnGloves")
    showDefaults("sfui_invpanel_filter_gloves")
    getTeamGloves()
}

const showRifles = () => {
    sideBtnHandler("sideBtnRifles")
    showDefaults("csgo_inventory_weapon_category_rifles")
}

const showPistols = () => {
    sideBtnHandler("sideBtnPistols")
    showDefaults("csgo_inventory_weapon_category_pistols")
}

const showSmgs = () => {
    sideBtnHandler("sideBtnSmgs")
    showDefaults("csgo_inventory_weapon_category_smgs")
}

const showShotguns = () => {
    sideBtnHandler("sideBtnShotguns")
    showDefaults("csgo_inventory_weapon_category_heavy")
}

const showUtility = () => {
    sideBtnHandler("sideBtnUtility")
    showDefaults("csgo_inventory_weapon_category_utility")
}

const showCTAgents = () => {
    sideBtnHandler("sideBtnCTAgents")
    showAgents("ct")
}

const showTAgents = () => {
    sideBtnHandler("sideBtnTAgents")
    showAgents("t")
}

const showMusic = () => {
    sideBtnHandler("sideBtnMusic")
    showMusicKits()
}

window.showKnives = showKnives
window.showGloves = showGloves
window.showRifles = showRifles
window.showPistols = showPistols
window.showSmgs = showSmgs
window.showShotguns = showShotguns
window.showUtility = showUtility
window.showCTAgents = showCTAgents
window.showTAgents = showTAgents
window.showMusic = showMusic

const sideBtns = document.querySelectorAll("[data-type='sideBtn']")
sideBtns.forEach(btn => {
    let attribute = btn.getAttribute("data-btn-type")
    switch (attribute) {
        case "knives":
            btn.addEventListener("click", showKnives)
            break;
        case "gloves":
            btn.addEventListener("click", showGloves)
            break;
        case "rifles":
            btn.addEventListener("click", showRifles)
            break;
        case "pistols":
            btn.addEventListener("click", showPistols)
            break;
        case "smgs":
            btn.addEventListener("click", showSmgs)
            break;
        case "utility":
            btn.addEventListener("click", showUtility)
            break;
        case "ctAgents":
            btn.addEventListener("click", showCTAgents)
            break;
        case "tAgents":
            btn.addEventListener("click", showTAgents)
            break;
        case "music":
            btn.addEventListener("click", showMusic)
            break;
        default:
            break;
    }
})

// NOTE: Calls to this function from 'templates.js' doesn't happen sometimes? (if ran without timeouts, see other NOTE below)
window.getWeaponSkins = (type, weaponid) => {
    socket.emit("get-weapon-skins", { steamid: user.id, weaponid: weaponid, type: type })
}

window.getTeamKnives = () => {
    socket.emit("get-team-knives", { steamid: user.id })
}

window.changeKnife = (weaponid, teamid = 0) => {
    let isUnequipAction = false

    if (teamid != 0) {
        const teamKey = (teamid == 2) ? 't' : 'ct'
        const equipToTeam = document.getElementById(`equip-${teamKey}-${weaponid}`)
        if (equipToTeam && !equipToTeam.checked) {
            unequipKnife(weaponid, teamid)
            isUnequipAction = true
        }
    }

    const loadingEl = document.getElementById(`loading-${weaponid}`)
    if (loadingEl) {
        loadingEl.classList.add("show")
    }

    socket.emit("change-knife", { knifename: weaponid, knifeid: weaponIds[weaponid], steamid: user.id, teamid: teamid, isunequip: isUnequipAction })
}

window.getTeamGloves = () => {
    socket.emit("get-team-gloves", { steamid: user.id })
}

window.changeGloves = (weaponid, teamid = 0) => {
    let isUnequipAction = false

    if (teamid != 0) {
        const teamKey = (teamid == 2) ? 't' : 'ct'
        const equipToTeam = document.getElementById(`equip-${teamKey}-${weaponIds[weaponid]}`)
        if (equipToTeam && !equipToTeam.checked) {
            unequipGloves(weaponIds[weaponid], teamid)
            isUnequipAction = true
        }
    }

    const loadingEl = document.getElementById(`loading-${weaponid}`)
    if (loadingEl) {
        loadingEl.classList.add("show")
    }

    socket.emit("change-gloves", { glovesid: weaponIds[weaponid], steamid: user.id, teamid: teamid, isunequip: isUnequipAction })
}

window.revertEquippedGloves = (weaponid, steamid, teamid = 0) => {
    socket.emit("revert-equipped-gloves", { weaponid: weaponid, steamid: steamid, teamid: teamid })
}

window.changeSkin = (weaponid, paintid, teamid = 0) => {
    // Disable tooltip on click
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]'))

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        bootstrap.Tooltip.getOrCreateInstance(tooltipTriggerEl).dispose()
    })

    let equipIndex = paintid
    let isResetAction = false

    if (teamid != 0) {
        // Skin selection menu for weapon
        const teamKey = (teamid == 2) ? 't' : 'ct'
        let equipToTeam = document.getElementById(`equip-${teamKey}-${equipIndex}`)

        // Adjust element id if inside main weapons menu
        if (equipToTeam == null) {
            equipIndex = getKeyByValue(weaponIds, Number(weaponid))
            equipToTeam = document.getElementById(`equip-${teamKey}-${equipIndex}`)
        }

        if (equipToTeam && !equipToTeam.checked) {
            resetSkin(weaponid, teamid)
            isResetAction = true
        }
    }

    let spinner = (equipIndex != paintid) ? document.getElementById(`loading-${equipIndex}`) : document.getElementById(`loading-${weaponid}`)

    if (spinner) {
        spinner.classList.add("show")
    }

    socket.emit("change-skin", { steamid: user.id, weaponid: weaponid, paintid: paintid, teamid: teamid, isreset: isResetAction })
}

window.changeAgent = (steamid, model, team) => {
    socket.emit("change-agent", { steamid: steamid, model: model, team: team })

    const loadingEl = document.getElementById(`loading-${model}`)
    if (loadingEl) {
        loadingEl.classList.add("show")
    }
}

window.changeMusic = (steamid, id) => {
    socket.emit("change-music", { steamid: steamid, id: Number(id) })

    const loadingEl = document.getElementById(`loading-${id}`)
    if (loadingEl) {
        loadingEl.classList.add("show")
    }
}

window.unequipKnife = (knifeid, teamid) => {
    socket.emit("unequip-knife", { steamid: user.id, teamid: teamid, knifeid: knifeid })
}

window.unequipGloves = (glovesid, teamid) => {
    socket.emit("unequip-gloves", { steamid: user.id, teamid: teamid, glovesid: glovesid })
}

window.resetSkin = (weaponid, teamid = 0) => {
    socket.emit("reset-skin", { steamid: user.id, weaponid: weaponid, teamid: teamid })
}

let secondarySkinElement = null
socket.on("weapon-skins-retrieved", data => {
    const weaponName = getKeyByValue(weaponIds, Number(data.weaponid))
    const weaponCard = document.getElementById(weaponName);
    if (!weaponCard) return;

    // Reset layout for possible dual-skin scenario
    weaponCard.classList.remove("dual-skin");
    const existingSecondary = document.getElementById(`${weaponName}-secondary-skin`);
    if (existingSecondary) existingSecondary.remove();
    weaponCard.parentElement && weaponCard.parentElement.classList.replace("col-md-5", "col-sm-4");


    data.weaponSkins.forEach(function (skin) {
        const weaponSkinTeam = (skin.weapon_team == 2) ? "t" : "ct";

        if (data.type == "guns") {
            const equipToTeam = document.getElementById(`equip-${weaponSkinTeam}-${weaponName}`);

            if (equipToTeam) {
                equipToTeam.checked = true;
                if (equipToTeam.labels && equipToTeam.labels[0]) {
                    equipToTeam.labels[0].classList.remove("unchecked-hover-effect");
                    equipToTeam.labels[0].title = (skin.weapon_team == 2) ? "Unequip from T loadout" : "Unequip from CT loadout";
                }
                equipToTeam.onclick = function () { changeSkin(data.weaponid, skin.weapon_paint_id, skin.weapon_team); }
            }

            if (data.weaponSkins.length == 1) {
                const oppositeTeam = (skin.weapon_team == 2) ? "ct" : "t";
                const equipToOppositeTeam = document.getElementById(`equip-${oppositeTeam}-${weaponName}`);
                if (equipToOppositeTeam) {
                    equipToOppositeTeam.onclick = function () { changeSkin(data.weaponid, skin.weapon_paint_id, (oppositeTeam == "t" ? 2 : 3)); }
                }
            }
        }
    });

    const gunElement = document.getElementById(weaponName);
    if (gunElement) {
        const button = gunElement.querySelectorAll("button");
        if (data.type == "guns" && button.length > 0) {
            button[button.length - 1].onclick = function () { showSkins(`${weaponName}`, true); }
        }
    }
});

socket.on("team-knives-retrieved", data => {
    data.knives.forEach(function (knife, index) {
        if (knife.knife != "weapon_knife") {
            // T
            if (knife.weapon_team == 2) {
                const equipToT = document.getElementById(`equip-t-${knife.knife}`)

                if (equipToT) {
                    equipToT.checked = true
                    if (equipToT.labels && equipToT.labels[0]) {
                        equipToT.labels[0].classList.remove("unchecked-hover-effect")
                        equipToT.labels[0].title = "Unequip from T loadout"
                    }
                }
                // CT
            } else if (knife.weapon_team == 3) {
                const equipToCT = document.getElementById(`equip-ct-${knife.knife}`)

                if (equipToCT) {
                    equipToCT.checked = true
                    if (equipToCT.labels && equipToCT.labels[0]) {
                        equipToCT.labels[0].classList.remove("unchecked-hover-effect")
                        equipToCT.labels[0].title = "Unequip from CT loadout"
                    }
                }
            }
        }
    });

    if (data.knives.length == 2) {
        if (data.knives[0].knife == data.knives[1].knife) {
            const knifeId = data.knives[0].knife

            if (knifeId != "weapon_knife") {
                const knifeElement = document.getElementById(knifeId)
                if (knifeElement) {
                    const button = knifeElement.querySelectorAll("button")
                    if (button.length > 0) {
                        button[button.length - 1].onclick = function () { showSkins(`${knifeId}`, true) }
                    }
                }
            }
        } else {
            const tKnifeId = data.knives[0].knife
            const ctKnifeId = data.knives[1].knife

            if (tKnifeId != "weapon_knife") {
                const tKnifeElement = document.getElementById(tKnifeId)
                if (tKnifeElement) {
                    const tChangeSkinBtn = tKnifeElement.querySelectorAll("button")
                    if (tChangeSkinBtn.length > 0) {
                        tChangeSkinBtn[tChangeSkinBtn.length - 1].onclick = function () { showSkins(`${tKnifeId}`) }
                    }
                }
            }

            if (ctKnifeId != "weapon_knife") {
                const ctKnifeElement = document.getElementById(ctKnifeId)
                if (ctKnifeElement) {
                    const ctChangeSkinBtn = ctKnifeElement.querySelectorAll("button")
                    if (ctChangeSkinBtn.length > 0) {
                        ctChangeSkinBtn[ctChangeSkinBtn.length - 1].onclick = function () { showSkins(`${ctKnifeId}`) }
                    }
                }
            }
        }
    }
})

socket.on("knife-changed", data => {
    const loadingEl = document.getElementById(`loading-${data.newKnifeName}`)
    if (loadingEl) {
        loadingEl.classList.remove("show")
    }

    // Player is equipping a knife to the other team whilst already having it equipped to one team
    if (data.oldKnivesWithTeamId.length == 1) {
        const oldKnife = data.oldKnivesWithTeamId[0]
        const oldEquipToTeam = document.getElementById(`equip-${(oldKnife.weapon_team == 2) ? 't' : 'ct'}-${oldKnife.knife}`)
        if (oldEquipToTeam) {
            oldEquipToTeam.checked = false
            oldEquipToTeam.labels[0].classList.add("unchecked-hover-effect")
            oldEquipToTeam.labels[0].title = (oldKnife.weapon_team == 2) ? "Equip to T loadout" : "Equip to CT loadout"
        }

        // Player is equipping a knife to both teams without already having it equipped anywhere
    } else {
        data.oldKnivesWithTeamId.forEach(function (oldKnife, index) {
            const oldEquipToTeam = document.getElementById(`equip-${(oldKnife.weapon_team == 2) ? 't' : 'ct'}-${oldKnife.knife}`)
            if (oldEquipToTeam) {
                oldEquipToTeam.checked = false
                oldEquipToTeam.labels[0].classList.add("unchecked-hover-effect")
                oldEquipToTeam.labels[0].title = (oldKnife.weapon_team == 2) ? "Equip to T loadout" : "Equip to CT loadout"
            }
        })
    }

    getTeamKnives()
})

socket.on("team-gloves-retrieved", data => {
    data.gloves.forEach(function (gloves, index) {
        if (gloves.weapon_defindex > 0) {
            // T
            if (gloves.weapon_team == 2) {
                const equipToT = document.getElementById(`equip-t-${gloves.weapon_defindex}`)
                if (equipToT) {
                    equipToT.checked = true
                    if (equipToT.labels && equipToT.labels[0]) {
                        equipToT.labels[0].classList.remove("unchecked-hover-effect")
                        equipToT.labels[0].title = "Unequip from T loadout"
                    }
                }
                // CT
            } else if (gloves.weapon_team == 3) {
                const equipToCT = document.getElementById(`equip-ct-${gloves.weapon_defindex}`)
                if (equipToCT) {
                    equipToCT.checked = true
                    if (equipToCT.labels && equipToCT.labels[0]) {
                        equipToCT.labels[0].classList.remove("unchecked-hover-effect")
                        equipToCT.labels[0].title = "Unequip from CT loadout"
                    }
                }
            }

            let secondaryGloves = document.getElementById(`${getKeyByValue(weaponIds, gloves.weapon_defindex)}-secondary-skin`)
            if (secondaryGloves != null) {
                secondarySkinElement = secondaryGloves
            }
        }
    })

    if (data.gloves.length == 2) {
        if (data.gloves[0].weapon_defindex == data.gloves[1].weapon_defindex) {
            const glovesId = getKeyByValue(weaponIds, data.gloves[0].weapon_defindex)

            if (glovesId != null) {
                const glovesEl = document.getElementById(glovesId)
                if (glovesEl) {
                    const buttons = glovesEl.querySelectorAll("button")
                    if (buttons.length > 0) {
                        buttons[buttons.length - 1].onclick = function () { showSkins(`${glovesId}`, true) }
                    }
                }
            }
        } else {
            if (data.gloves[0].weapon_defindex > 0) {
                const tGlovesId = getKeyByValue(weaponIds, data.gloves[0].weapon_defindex)
                const tGlovesEl = document.getElementById(tGlovesId)
                if (tGlovesEl) {
                    const tButtons = tGlovesEl.querySelectorAll("button")
                    if (tButtons.length > 0) {
                        tButtons[tButtons.length - 1].onclick = function () { showSkins(`${tGlovesId}`) }
                    }
                }
            }

            if (data.gloves[1].weapon_defindex > 0) {
                const ctGlovesId = getKeyByValue(weaponIds, data.gloves[1].weapon_defindex)
                const ctGlovesEl = document.getElementById(ctGlovesId)
                if (ctGlovesEl) {
                    const ctButtons = ctGlovesEl.querySelectorAll("button")
                    if (ctButtons.length > 0) {
                        ctButtons[ctButtons.length - 1].onclick = function () { showSkins(`${ctGlovesId}`) }
                    }
                }
            }
        }
    }
})


socket.on("gloves-changed", data => {
    const gloves = getKeyByValue(weaponIds, data.newGlovesId)
    const loadingEl = document.getElementById(`loading-${gloves}`)
    if (loadingEl) {
        loadingEl.classList.remove("show")
    }

    // Player is equipping pair of gloves to other team whilst already having it equipped to one team
    if (data.oldGlovesWithTeamId.length == 1) {
        const oldGloves = data.oldGlovesWithTeamId[0]

        if (oldGloves != null) {
            const oldEquipToTeam = document.getElementById(`equip-${(oldGloves.weapon_team == 2) ? 't' : 'ct'}-${oldGloves.weapon_defindex}`)

            if (oldEquipToTeam) {
                oldEquipToTeam.checked = false
                oldEquipToTeam.labels[0].classList.add("unchecked-hover-effect")
                oldEquipToTeam.labels[0].title = (oldGloves.weapon_team == 2) ? "Equip to T loadout" : "Equip to CT loadout"
            }
        }

        // Player is equipping pair of gloves to both teams without having any equipped in the beginning
    } else {
        data.oldGlovesWithTeamId.forEach(function (oldGloves, index) {
            const oldEquipToTeam = document.getElementById(`equip-${(oldGloves.weapon_team == 2) ? 't' : 'ct'}-${oldGloves.weapon_defindex}`)

            if (oldEquipToTeam) {
                oldEquipToTeam.checked = false
                oldEquipToTeam.labels[0].classList.add("unchecked-hover-effect")
                oldEquipToTeam.labels[0].title = (oldGloves.weapon_team == 2) ? "Equip to T loadout" : "Equip to CT loadout"
            }
        })
    }

    getTeamGloves()
})

// Reverting here means to re-equip a pair of gloves to empty team if already equipped to an existing team
socket.on("reverted-equipped-gloves", data => {
    if (data.oppositeGlovesId > 0) {
        const oppositeTeam = (data.teamid == 2) ? "t" : "ct"
        const oppositeEquipToTeam = document.getElementById(`equip-${oppositeTeam}-${data.oppositeGlovesId}`)
        oppositeEquipToTeam.checked = true
        oppositeEquipToTeam.labels[0].classList.remove("unchecked-hover-effect")
        oppositeEquipToTeam.labels[0].title = (oppositeTeam == "t") ? "Unequip from T loadout" : "Unequip from CT loadout"

    }
})

socket.on("skin-changed", data => {
    let weaponName = getKeyByValue(weaponIds, Number(data.weaponid))
    let skinIndex = (document.getElementById(`loading-${weaponName}`) != null) ? weaponName : `${data.weaponid}-${data.paintid}`

    let elms = document.getElementsByClassName("weapon-card")

    for (var i = 0; i < elms.length; i++) {
        elms[i].classList.remove("active-card")
    }

    window.selectedSkins = data.playerSkins
    sessionStorage.setItem("selected_skins", JSON.stringify(window.selectedSkins))

    const updatedSkin = selectedSkins.find(o => o["weapon_defindex"] === Number(data["weaponid"]))
    const insideSkinsMenu = skinIndex != weaponName

    if (insideSkinsMenu) {
        document.getElementById(`weapon-${skinIndex}`).classList.add("active-card")
        const loadingEl = document.getElementById(`loading-${skinIndex}`)
        if (loadingEl) {
            loadingEl.classList.remove("show")
        }

        // Update weapon skin parameters here if skin is only equipped for one team
        if (!document.body.contains(document.getElementById(`equip-t-${data.paintid}`))) {
            const floatValue = parseFloat(updatedSkin["weapon_wear"].toFixed(6))

            document.getElementById("floatSlider").value = floatValue
            document.getElementById("float").value = floatValue
            updateFloatText(floatValue)

            document.getElementById("pattern").value = updatedSkin["weapon_seed"]
        }

        // NOTE: These timeouts are temporary until I make sure the code below runs consistently (def a race condition somewhere...)
        if (document.body.contains(document.getElementById(`equip-t-${data.paintid}`))) {
            setTimeout(() => { showSkins(getKeyByValue(weaponIds, updatedSkin.weapon_defindex), true) }, 100)
        } else {
            setTimeout(() => { showSkins(getKeyByValue(weaponIds, updatedSkin.weapon_defindex)) }, 100)
        }
    } else {
        document.querySelectorAll(".active-side")[0].onclick()
    }

    setTimeout(() => { window.scrollTo(0, sessionStorage.getItem("last_scrolled_position")) }, 100)
})

socket.on("agent-changed", data => {
    let elms = document.getElementsByClassName("weapon-card")

    for (var i = 0; i < elms.length; i++) {
        elms[i].classList.remove("active-card")
    }

    if (data.agents && data.agents.length > 0) {
        selectedAgents = data.agents[0]
    }

    const agentCard = document.getElementById(`agent-${data.currentAgent}`)
    const agentLoading = document.getElementById(`loading-${data.currentAgent}`)

    if (agentCard) agentCard.classList.add("active-card")
    if (agentLoading) {
        agentLoading.style.opacity = 0
        agentLoading.style.visibility = "hidden"
    }
})

socket.on("music-changed", data => {
    let elms = document.getElementsByClassName("weapon-card")

    for (var i = 0; i < elms.length; i++) {
        elms[i].classList.remove("active-card")
    }

    selectedMusicKit = data.music[0]

    document.getElementById(`music-${data.currentMusic}`).classList.add("active-card")
    document.getElementById(`loading-${data.currentMusic}`).style.opacity = 0
    document.getElementById(`loading-${data.currentMusic}`).style.visibility = "hidden"
})

socket.on("knife-unequipped", data => {
    const equipToTeam = document.getElementById(`equip-${(data.teamid == 2) ? 't' : 'ct'}-${data.knifeid}`)

    if (equipToTeam) {
        equipToTeam.checked = false
        if (equipToTeam.labels && equipToTeam.labels[0]) {
            equipToTeam.labels[0].title = (data.teamid == 2) ? "Equip to T loadout" : "Equip to CT loadout"
        }
    }

    if (!data.knivesLeft) {
        const button = document.getElementById(data.knifeid).querySelectorAll("button")
        button[button.length - 1].onclick = function () { showSkins(`${data.knifeid}`) }
    }
})

socket.on("gloves-unequipped", data => {
    const equipToTeam = document.getElementById(`equip-${(data.teamid == 2) ? 't' : 'ct'}-${data.glovesid}`)

    equipToTeam.checked = false
})

socket.on("skin-reset", data => {
    const weaponName = getKeyByValue(weaponIds, Number(data.weaponid))

    // Restore default weapon image (from one selected skin to none)
    const skinImg = document.getElementById(`img-${weaponName}`)
    if (skinImg != null && !data.weaponSkinsLeft) {
        skinImg.src = skinImg.alt
        skinImg.style.filter = ""
        skinImg.style = "object-fit: contain; aspect-ratio: 512 / 384"
        skinImg.parentNode.parentNode.classList.replace("flex-row", "flex-column")

        document.getElementById(`reset-${weaponName}`).outerHTML = ""
        document.getElementById(`skin-title-${weaponName}`).innerHTML = "Default"
        document.getElementById(`skin-title-${weaponName}`).style = "color: rgb(108, 127, 125); font-size: 0.93rem"
    }

    // Update array of selected skins
    let tempSkins = [];

    selectedSkins.forEach(element => {
        if (element.weapon_defindex != data.weaponid) {
            tempSkins.push(element)
        }
    })

    window.selectedSkins = tempSkins

    if (!data.weaponSkinsLeft) {
        sessionStorage.setItem("selected_skins", JSON.stringify(window.selectedSkins))
    }

    // Only disable teams button group for default gloves and weapons
    let weaponType = "gun"
    defaultsObject.forEach(weapon => {
        if (weapon.weapon_type == "sfui_invpanel_filter_melee") {
            if (weapon.weapon_name == weaponName) {
                weaponType = "knives"
                return true
            }
        } else if (weapon.weapon_type == "sfui_invpanel_filter_gloves") {
            if (weapon.weapon_name == weaponName) {
                weaponType = "gloves"
                return true
            }
        }
    })

    // Delete default/secondary weapon skin image and re-add remaining skin (if any)
    const hasDefaultOrSecondarySkin = secondarySkinElement && (secondarySkinElement.id == `${weaponName}-default-skin` || secondarySkinElement.id == `${weaponName}-secondary-skin`)
    if (hasDefaultOrSecondarySkin) {
        if (secondarySkinElement.parentNode) {
            secondarySkinElement.parentNode.remove()
        }

        secondarySkinElement.remove()

        let remainingSkinData = null;
        Object.keys(defaultsObject).forEach(x => remainingSkinData = Number(defaultsObject[x].weapon_defindex) === data.weaponid ? defaultsObject[x] : remainingSkinData)

        let hasTeamBtns = document.getElementById(`equip-t-${getKeyByValue(weaponIds, Number(data.weaponid))}`) != null
        if (weaponType == "gloves" && !hasTeamBtns) {
            hasTeamBtns = true
        }

        const imagesOnSkinCard = document.getElementById(weaponName).querySelectorAll("a").length

        if (remainingSkinData != null && hasTeamBtns && imagesOnSkinCard != 1) {
            let skinCard = document.createElement("a")
            skinCard.classList.add("text-decoration-none", "d-flex", "flex-column", "default-hover-effect")
            skinCard.style = "z-index: 0"

            skinCard.innerHTML = `
	    	<div class="d-flex flex-column">
                    <img src="${remainingSkinData.image}" class="weapon-img mx-auto my-3" loading="lazy" alt="${remainingSkinData.paint_name}" style="object-fit: contain; aspect-ratio: 512 / 384;">

                    <p class="m-0 text-light weapon-skin-title mx-auto text-center" style="color: rgb(108, 127, 125); font-size: 0.93rem" id="skin-title-${remainingSkinData.paint_name}">Default</p>
	        </div>
	    `

            document.getElementById(`loading-${weaponName}`).insertAdjacentElement("afterend", skinCard)
            document.getElementById(`${weaponName}`).parentNode.classList.replace("col-md-5", "col-sm-4")
        }
    } else {
        // Restore scrolling position in skins menu
        if (!data.weaponSkinsLeft && data.teamid != 0 && document.getElementById(`equip-t-${weaponName}`) == null) {
            showSkins(weaponName, true)
            window.scrollTo(0, sessionStorage.getItem("last_scrolled_position"))
        }
    }

    const weaponId = (weaponType == "gloves") ? data.weaponid : (weaponType == "gun") ? weaponName : "";
    const equipAsT = document.getElementById(`equip-t-${weaponId}`)
    const equipAsCT = document.getElementById(`equip-ct-${weaponId}`)

    // Disable teams button group if skins are not yet selected (for gloves and guns only)
    if (!data.weaponSkinsLeft && weaponType != "knives") {
        const teamId = (equipAsT == null && equipAsCT == null) ? 0 : (equipAsT.checked && !equipAsCT.checked) ? 2 : (!equipAsT.checked && equipAsCT.checked) ? 3 : 0

        if (weaponType == "gloves") {
            revertEquippedGloves(weaponId, user.id, teamId)
        }

        if (equipAsT != null && equipAsCT != null) {
            equipAsT.checked = false
            equipAsCT.checked = false

            if (weaponType == "gloves" || weaponType == "gun") {
                equipAsT.disabled = true
                equipAsCT.disabled = true
            }

            const teamsBtnGroup = equipAsT.parentNode
            teamsBtnGroup.setAttribute("data-bs-toggle", "tooltip")
            teamsBtnGroup.setAttribute("data-bs-placement", "bottom")
            teamsBtnGroup.setAttribute("data-bs-title", "Choose a skin first!")
            teamsBtnGroup.parentNode.classList.add("default-hover-effect")

            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }
    }
})

window.showSkins = (skinType, showTeams = false) => {
    // Show Back buttons
    const backBtn = document.getElementById("btnBackSidebar");
    const backBtnMobile = document.getElementById("mobileBackContainer");
    if (backBtn) backBtn.style.display = "flex";
    if (backBtnMobile) backBtnMobile.style.display = "block";

    document.getElementById("skinsContainer").innerHTML = ""

    // Keep selected skins up-to-date (Moved outside loop for efficiency)
    const storedSkins = sessionStorage.getItem("selected_skins");
    if (storedSkins && storedSkins !== "undefined" && storedSkins !== "null") {
        try {
            window.selectedSkins = JSON.parse(storedSkins);
        } catch (e) {
            console.error("Error parsing selected_skins from sessionStorage", e);
        }
    }

    // Ensure selectedSkins is at least an empty array
    const currentSkins = window.selectedSkins || [];

    skinsObject.forEach(element => {
        if (element.weapon.id == skinType) {
            let rarities = {
                "#b0c3d9": "common",
                "#5e98d9": "uncommon",
                "#4b69ff": "rare",
                "#8847ff": "mythical",
                "#d32ce6": "legendary",
                "#eb4b4b": "ancient",
                "#e4ae39": "contraband"
            }

            let bgColor = "rarity-uncommon"
            let phase = ""
            let active = ""
            let steamid = user.id
            let weaponid = weaponIds[element.weapon.id]
            let paintid = element.paint_index
            let float = 0.000001
            let seed = 0

            // Get color of item for card
            if (element.category.id == "sfui_invpanel_filter_melee") {
                // Gold for all knives
                bgColor = "rarity-gold"
            } else {
                // Anything else
                bgColor = `rarity-${rarities[element.rarity.color]}`
            }

            // For 'Doppler' phases
            if (typeof element.phase != "undefined") {
                phase = `(${element.phase})`
            }

            let tEquippedPaintId = null
            let ctEquippedPaintId = null

            // Make outline if this skin is selected
            currentSkins.forEach(el => {
                if (el.weapon_paint_id == element.paint_index && (el.weapon_defindex == weaponIds[element.weapon.id] || el.model_idx == weaponIds[element.weapon.id])) {
                    active = "active-card"
                    float = el.weapon_wear
                    seed = el.weapon_seed

                    // T
                    if (el.weapon_team == 2) {
                        tEquippedPaintId = el.weapon_paint_id
                    }
                    // CT
                    else if (el.weapon_team == 3) {
                        ctEquippedPaintId = el.weapon_paint_id
                    }
                }
            })

            let card = document.createElement("div")
            card.classList.add("col-6", "col-sm-4", "col-md-3", "p-2")

            card.innerHTML = `
                <div onclick="changeSkin(\'${weaponIds[element.weapon.id]}\', ${element.paint_index})" id="weapon-${weaponIds[element.weapon.id]}-${element.paint_index}" class="weapon-card rounded-3 d-flex flex-column ${active} ${bgColor} contrast-reset pb-2" data-type="skinCard" data-btn-type="${weaponIds[element.weapon.id]}-${element.paint_index}" data-bs-title="${element.pattern.name} ${phase}" style="cursor: pointer">


                    <button onclick="editModal(\'${element.image}\', \'${element.weapon.name}\', \'${element.pattern.name} ${phase}\', \'${float}\', \'${seed}\', \'${element.weapon.id}\', \'${element.paint_index}\'); event.stopPropagation()" class="settings" data-bs-toggle="modal" data-bs-target="#patternFloat">
                        <i class="fa-solid fa-gear"></i>
                    </button>

                    <img src="${element.image}" class="weapon-img mx-auto my-3" loading="lazy" alt="${element.name}">
                    
                    <div class="d-flex align-items-center g-3">
                        <p class="m-0 ms-3 text-secondary">
                            <small class="text-roboto">
                                ${element.weapon.name}
                            </small>
                        </p>
                        <div class="skin-dot mx-2"></div>
                    </div>
                    
                    <h5 class="weapon-skin-title text-roboto ms-3 pe-4" id="skin-${element.paint_index}-name">
                        ${element.pattern.name} ${phase}
                    </h5>
                </div>
            `

            document.getElementById("skinsContainer").appendChild(card)

            // Show full weapon skin name in tooltip if text overflows
            const skin = document.getElementById(`weapon-${weaponIds[element.weapon.id]}-${element.paint_index}`)
            const skinName = document.getElementById(`skin-${element.paint_index}-name`)

            if (isEllipsisActive(skinName)) {
                skin.setAttribute("data-bs-toggle", "tooltip")
                skin.setAttribute("data-bs-placement", "bottom")

                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
            }

            // Add teams button group and check them if equipped
            if (showTeams) {
                const skinCard = document.getElementById(`weapon-${weaponIds[element.weapon.id]}-${element.paint_index}`)

                const teamsBtnGroup = document.createElement("div")
                teamsBtnGroup.innerHTML = `
                    <div class="team-selection-group ms-3 mb-2">
                        <input onclick="changeSkin(\'${weaponIds[element.weapon.id]}\', ${element.paint_index}, 2); event.stopPropagation()" type="checkbox" class="btn-check" id="equip-t-${element.paint_index}" autocomplete="off">
                        <label onclick="event.stopPropagation()" class="team-btn-equip btn-t" for="equip-t-${element.paint_index}" title="Equip to T loadout">
                            <img class="team-logo" src="/icons/t-logo.png">
                        </label>
                        <input onclick="changeSkin(\'${weaponIds[element.weapon.id]}\', ${element.paint_index}, 3); event.stopPropagation()" type="checkbox" class="btn-check" id="equip-ct-${element.paint_index}" autocomplete="off">
                        <label onclick="event.stopPropagation()" class="team-btn-equip btn-ct" for="equip-ct-${element.paint_index}" title="Equip to CT loadout">
                            <img class="team-logo" src="/icons/ct-logo.png">
                        </label>
                    </div>
		    `

                document.getElementById(`weapon-${weaponIds[element.weapon.id]}-${element.paint_index}`).appendChild(teamsBtnGroup)

                const equipToT = (tEquippedPaintId != null) ? document.getElementById(`equip-t-${tEquippedPaintId}`) : null
                const equipToCT = (ctEquippedPaintId != null) ? document.getElementById(`equip-ct-${ctEquippedPaintId}`) : null

                if (equipToT != null && equipToCT != null && tEquippedPaintId == ctEquippedPaintId) {
                    equipToT.checked = true
                    if (equipToT.labels && equipToT.labels[0]) equipToT.labels[0].title = "Unequip from T loadout"

                    equipToCT.checked = true
                    if (equipToCT.labels && equipToCT.labels[0]) equipToCT.labels[0].title = "Unequip from CT loadout"
                }

                if (equipToT != null && tEquippedPaintId != ctEquippedPaintId && tEquippedPaintId == element.paint_index) {
                    equipToT.checked = true
                    if (equipToT.labels && equipToT.labels[0]) equipToT.labels[0].title = "Unequip from T loadout"
                }

                if (equipToCT != null && ctEquippedPaintId != tEquippedPaintId && ctEquippedPaintId == element.paint_index) {
                    equipToCT.checked = true
                    if (equipToCT.labels && equipToCT.labels[0]) equipToCT.labels[0].title = "Unequip from CT loadout"
                }
            }
        }
    });
}

window.addEventListener("scroll", () => {
    sessionStorage.setItem("last_scrolled_position", window.scrollY)
})
