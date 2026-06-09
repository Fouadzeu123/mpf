import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/membre/paiement',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PaymentController::create
 * @see app/Http/Controllers/PaymentController.php:29
 * @route '/membre/paiement'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
export const initiate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiate.url(options),
    method: 'post',
})

initiate.definition = {
    methods: ["post"],
    url: '/membre/paiement',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
initiate.url = (options?: RouteQueryOptions) => {
    return initiate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
initiate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiate.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
    const initiateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initiate.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PaymentController::initiate
 * @see app/Http/Controllers/PaymentController.php:40
 * @route '/membre/paiement'
 */
        initiateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initiate.url(options),
            method: 'post',
        })
    
    initiate.form = initiateForm
/**
* @see \App\Http\Controllers\PaymentController::initiateContribution
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
export const initiateContribution = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiateContribution.url(args, options),
    method: 'post',
})

initiateContribution.definition = {
    methods: ["post"],
    url: '/membre/evenements/{event}/contribuer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PaymentController::initiateContribution
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
initiateContribution.url = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { event: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { event: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    event: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        event: typeof args.event === 'object'
                ? args.event.id
                : args.event,
                }

    return initiateContribution.definition.url
            .replace('{event}', parsedArgs.event.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::initiateContribution
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
initiateContribution.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initiateContribution.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PaymentController::initiateContribution
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
    const initiateContributionForm = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initiateContribution.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PaymentController::initiateContribution
 * @see app/Http/Controllers/PaymentController.php:100
 * @route '/membre/evenements/{event}/contribuer'
 */
        initiateContributionForm.post = (args: { event: number | { id: number } } | [event: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initiateContribution.url(args, options),
            method: 'post',
        })
    
    initiateContribution.form = initiateContributionForm
/**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
export const callback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

callback.definition = {
    methods: ["get","head"],
    url: '/paiements/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
    const callbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: callback.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
        callbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: callback.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PaymentController::callback
 * @see app/Http/Controllers/PaymentController.php:61
 * @route '/paiements/callback'
 */
        callbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: callback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    callback.form = callbackForm
/**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
export const verify = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verify.url(args, options),
    method: 'get',
})

verify.definition = {
    methods: ["get","head"],
    url: '/paiements/verifier/{reference}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
verify.url = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { reference: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    reference: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        reference: args.reference,
                }

    return verify.definition.url
            .replace('{reference}', parsedArgs.reference.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
verify.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verify.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
verify.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: verify.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
    const verifyForm = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: verify.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
        verifyForm.get = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: verify.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PaymentController::verify
 * @see app/Http/Controllers/PaymentController.php:72
 * @route '/paiements/verifier/{reference}'
 */
        verifyForm.head = (args: { reference: string | number } | [reference: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: verify.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    verify.form = verifyForm
/**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/paiements',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PaymentController::index
 * @see app/Http/Controllers/PaymentController.php:22
 * @route '/paiements'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const PaymentController = { create, initiate, initiateContribution, callback, verify, index }

export default PaymentController