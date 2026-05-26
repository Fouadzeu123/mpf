import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import loginDf2c2a from './login'
import payment44796b from './payment'
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/membre/connexion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
    const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: login.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
        loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: login.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::login
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
        loginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: login.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    login.form = loginForm
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::logout
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/membre/deconnexion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::logout
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::logout
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\MemberAuthController::logout
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
    const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: logout.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::logout
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
        logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: logout.url(options),
            method: 'post',
        })
    
    logout.form = logoutForm
/**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
export const portal = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: portal.url(options),
    method: 'get',
})

portal.definition = {
    methods: ["get","head"],
    url: '/membre/espace',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
portal.url = (options?: RouteQueryOptions) => {
    return portal.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
portal.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: portal.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
portal.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: portal.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
    const portalForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: portal.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
        portalForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: portal.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MemberPortalController::portal
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
        portalForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: portal.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    portal.form = portalForm
/**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
export const payment = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: payment.url(options),
    method: 'get',
})

payment.definition = {
    methods: ["get","head"],
    url: '/membre/paiement',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
payment.url = (options?: RouteQueryOptions) => {
    return payment.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
payment.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: payment.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
payment.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: payment.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
    const paymentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: payment.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
        paymentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: payment.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PaymentController::payment
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
        paymentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: payment.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    payment.form = paymentForm
const member = {
    login: Object.assign(login, loginDf2c2a),
logout: Object.assign(logout, logout),
portal: Object.assign(portal, portal),
payment: Object.assign(payment, payment44796b),
}

export default member