import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/impression',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:18
 * @route '/impression'
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
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:28
 * @route '/impression/membres'
 */
export const members = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: members.url(options),
    method: 'post',
})

members.definition = {
    methods: ["post"],
    url: '/impression/membres',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:28
 * @route '/impression/membres'
 */
members.url = (options?: RouteQueryOptions) => {
    return members.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:28
 * @route '/impression/membres'
 */
members.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: members.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:28
 * @route '/impression/membres'
 */
    const membersForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: members.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:28
 * @route '/impression/membres'
 */
        membersForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: members.url(options),
            method: 'post',
        })
    
    members.form = membersForm
/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:37
 * @route '/impression/visiteurs'
 */
export const visitors = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: visitors.url(options),
    method: 'post',
})

visitors.definition = {
    methods: ["post"],
    url: '/impression/visiteurs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:37
 * @route '/impression/visiteurs'
 */
visitors.url = (options?: RouteQueryOptions) => {
    return visitors.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:37
 * @route '/impression/visiteurs'
 */
visitors.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: visitors.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:37
 * @route '/impression/visiteurs'
 */
    const visitorsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: visitors.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:37
 * @route '/impression/visiteurs'
 */
        visitorsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: visitors.url(options),
            method: 'post',
        })
    
    visitors.form = visitorsForm
const print = {
    index: Object.assign(index, index),
members: Object.assign(members, members),
visitors: Object.assign(visitors, visitors),
}

export default print