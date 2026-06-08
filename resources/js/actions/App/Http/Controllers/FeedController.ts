import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
const indexccce47e367625b4dd07f0167f7f80114 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexccce47e367625b4dd07f0167f7f80114.url(options),
    method: 'get',
})

indexccce47e367625b4dd07f0167f7f80114.definition = {
    methods: ["get","head"],
    url: '/membre/flux',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
indexccce47e367625b4dd07f0167f7f80114.url = (options?: RouteQueryOptions) => {
    return indexccce47e367625b4dd07f0167f7f80114.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
indexccce47e367625b4dd07f0167f7f80114.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexccce47e367625b4dd07f0167f7f80114.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
indexccce47e367625b4dd07f0167f7f80114.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexccce47e367625b4dd07f0167f7f80114.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
    const indexccce47e367625b4dd07f0167f7f80114Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexccce47e367625b4dd07f0167f7f80114.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
        indexccce47e367625b4dd07f0167f7f80114Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexccce47e367625b4dd07f0167f7f80114.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/membre/flux'
 */
        indexccce47e367625b4dd07f0167f7f80114Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexccce47e367625b4dd07f0167f7f80114.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexccce47e367625b4dd07f0167f7f80114.form = indexccce47e367625b4dd07f0167f7f80114Form
    /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
const indexbd8990080e8f2ecd43cfa9c6cd1a85ab = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url(options),
    method: 'get',
})

indexbd8990080e8f2ecd43cfa9c6cd1a85ab.definition = {
    methods: ["get","head"],
    url: '/flux',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url = (options?: RouteQueryOptions) => {
    return indexbd8990080e8f2ecd43cfa9c6cd1a85ab.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
indexbd8990080e8f2ecd43cfa9c6cd1a85ab.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
indexbd8990080e8f2ecd43cfa9c6cd1a85ab.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
    const indexbd8990080e8f2ecd43cfa9c6cd1a85abForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
        indexbd8990080e8f2ecd43cfa9c6cd1a85abForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\FeedController::index
 * @see app/Http/Controllers/FeedController.php:15
 * @route '/flux'
 */
        indexbd8990080e8f2ecd43cfa9c6cd1a85abForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexbd8990080e8f2ecd43cfa9c6cd1a85ab.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexbd8990080e8f2ecd43cfa9c6cd1a85ab.form = indexbd8990080e8f2ecd43cfa9c6cd1a85abForm

export const index = {
    '/membre/flux': indexccce47e367625b4dd07f0167f7f80114,
    '/flux': indexbd8990080e8f2ecd43cfa9c6cd1a85ab,
}

const FeedController = { index }

export default FeedController