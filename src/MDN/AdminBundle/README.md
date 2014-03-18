

## Install

### Configuring Symfony

Into your app/config.yml add the asset to the admin

Assetic Configuration

assetic:
    ...
    bundles:        [ "AnotherBundle", "MDNAdminBundle" ]


Add to your app/config/routing.yml the block:

    mdn_admin:
        resource: "@MDNAdminBundle/Resources/config/routing.yml"
        prefix:   /


Into your app/config/security.yml get sure you have the config:

    security:
        encoders:
            MDN\AdminBundle\Entity\User: sha512

        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

        providers:
            database: 
              entity: { class: MDNAdminBundle:User, property: username }

        firewalls:
            secured_area:
                pattern:    ^/
                form_login:
                    check_path: mdn_admin_login_check
                    login_path: mdn_admin_login_login
                logout:
                    path:   mdn_admin_login_logout
                    target: mdn_admin_login_login
                anonymous: ~

        access_control:
            - { path: ^/admin/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/admin/, roles: ROLE_ADMIN }
