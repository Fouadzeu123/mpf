import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
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
const login = {
    store: Object.assign(store, store),
}

export default login