import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/membre/espace',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MemberPortalController::index
 * @see app/Http/Controllers/MemberPortalController.php:16
 * @route '/membre/espace'
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
const MemberPortalController = { index }

export default MemberPortalController