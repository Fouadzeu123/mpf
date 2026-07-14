import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sainte-cene',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\CommunionController::index
 * @see app/Http/Controllers/CommunionController.php:13
 * @route '/sainte-cene'
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
/**
* @see \App\Http\Controllers\CommunionController::updateSettings
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
export const updateSettings = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateSettings.url(options),
    method: 'post',
})

updateSettings.definition = {
    methods: ["post"],
    url: '/sainte-cene/configurer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CommunionController::updateSettings
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
updateSettings.url = (options?: RouteQueryOptions) => {
    return updateSettings.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CommunionController::updateSettings
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
updateSettings.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateSettings.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\CommunionController::updateSettings
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
    const updateSettingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateSettings.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\CommunionController::updateSettings
 * @see app/Http/Controllers/CommunionController.php:30
 * @route '/sainte-cene/configurer'
 */
        updateSettingsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateSettings.url(options),
            method: 'post',
        })
    
    updateSettings.form = updateSettingsForm
const CommunionController = { index, updateSettings }

export default CommunionController