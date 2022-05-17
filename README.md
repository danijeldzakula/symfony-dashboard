# Login system
    + Roles (logovanje na stranicu putem role, kao i pristup stranicama za odredjen role)
    -----------------------------------------------------------------------------------------------------------

        -----------------------------------------------------------------------------------------------------------
        - ROLE_ADMIN
        ---------------------------------------------- START ------------------------------------------------------
            + Prikaz svih stranica za Administratora i njegove opcije:
            -----------------------------------------------------------------------------------------------------------
                ## Users - page:(localhost:/users)
                    Table:
                        --- Table Head ---
                            - Search: (search by "firstName" and "lastName")
                        --- Table Body ---
                            - Table:  (users: "avatar", "firstName", "lastName")
                            - Options: 
                                - View icon / pojedinacni prikaz programera - link example to redirect:(localhost:/user/{id})
                                    # Informacije o programeru:
                                        - visible sidebar
                                            EDIT user   - show hidden sidebar / form input:(avatar, firstName, lastName, email, role, status)
                                            VIEW user   - (avatar, firstName, lastName, email, role, status, this month)
                                            FILTER user - filtrirati po odabranom datumu i prikazati / form input:(od - do)
                                        - table
                                            VIEW all work - ((clientName), (taskTitle, dateCheck, workTime))
                -----------------------------------------------------------------------------------------------------------
                ## Clients - page:(localhost:/clients)
                    Table:
                        --- Table Head --- 
                            - Search: (search by "clientName")
                            - Button: (Add client) 
                                - Open sidebar with form for: (upload "avatar", "clientName")
                        --- Table Body ---
                            - Table: (clients: "avatar", "clientName")
                            - Options: 
                                - View icon / pojedinacni prikaz klijenta - link example to redirect:(localhost:/client/{id})
                                    # Informacije o klijentu:
                                        - visible sidebar
                                            EDIT client   - show hidden sidebar / form input:(avatar, clientName, email)
                                            VIEW client   - (avatar, clientName, email)
                                            FILTER client - filtrirati po odabranom datumu i prikazati / form input:(od - do)
                                        - table
                                            VIEW all work - ((user: "firstName" and "lastName"), (taskTitle, dateCheck, workTime))
                                - Delete icon / pojedinacno brisanje klijenta
                                    - show modal
                                        VIEW client - (clientName)
                -----------------------------------------------------------------------------------------------------------
                ## Account - page:(localhost:/account)

        ------------------------------------------- END / ROLE_ADMIN ----------------------------------------------



        -----------------------------------------------------------------------------------------------------------
        - ROLE_USERS
        ---------------------------------------------- start ------------------------------------------------------

        ---------------------------------------- END / ROLE_USERS --------------------------------------------


# Database create
    + Users
        - id (autoincrement)
        - firstName (varchar)
        - lastName (varchar)
        - avatar (varchar)
        - email (varchar)
        - password (varchar)(md5())
        - role (ROLE_USERS) or (ROLE_ADMIN)

    + Clients
        - id (autoincrement)
        - clientName (varchar)
        - avatar (varchar)
        - email (varchar)

    + Task
        - id (autoincrement)
        - users _id
        - clients _id
        - taskTitle
        - dataCheck
        - workTime
