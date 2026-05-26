import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/membre/connexion',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::create
 * @see app/Http/Controllers/Auth/MemberAuthController.php:15
 * @route '/membre/connexion'
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
* @see \App\Http\Controllers\Auth\MemberAuthController::store
 * @see app/Http/Controllers/Auth/MemberAuthController.php:20
 * @route '/membre/connexion'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/membre/connexion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::store
 * @see app/Http/Controllers/Auth/MemberAuthController.php:20
 * @route '/membre/connexion'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::store
 * @see app/Http/Controllers/Auth/MemberAuthController.php:20
 * @route '/membre/connexion'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\MemberAuthController::store
 * @see app/Http/Controllers/Auth/MemberAuthController.php:20
 * @route '/membre/connexion'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::store
 * @see app/Http/Controllers/Auth/MemberAuthController.php:20
 * @route '/membre/connexion'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Auth\MemberAuthController::destroy
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
export const destroy = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: destroy.url(options),
    method: 'post',
})

destroy.definition = {
    methods: ["post"],
    url: '/membre/deconnexion',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::destroy
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
destroy.url = (options?: RouteQueryOptions) => {
    return destroy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\MemberAuthController::destroy
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
destroy.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: destroy.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Auth\MemberAuthController::destroy
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
    const destroyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Auth\MemberAuthController::destroy
 * @see app/Http/Controllers/Auth/MemberAuthController.php:40
 * @route '/membre/deconnexion'
 */
        destroyForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(options),
            method: 'post',
        })
    
    destroy.form = destroyForm
const MemberAuthController = { create, store, destroy }

export default MemberAuthController