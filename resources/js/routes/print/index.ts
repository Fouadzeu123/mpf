import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
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
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
 * @route '/impression'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::index
 * @see app/Http/Controllers/PrintController.php:22
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
 * @see app/Http/Controllers/PrintController.php:32
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
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
members.url = (options?: RouteQueryOptions) => {
    return members.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
members.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: members.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
    const membersForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: members.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::members
 * @see app/Http/Controllers/PrintController.php:32
 * @route '/impression/membres'
 */
        membersForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: members.url(options),
            method: 'post',
        })
    
    members.form = membersForm
/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
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
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
visitors.url = (options?: RouteQueryOptions) => {
    return visitors.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
visitors.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: visitors.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
    const visitorsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: visitors.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\PrintController::visitors
 * @see app/Http/Controllers/PrintController.php:41
 * @route '/impression/visiteurs'
 */
        visitorsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: visitors.url(options),
            method: 'post',
        })
    
    visitors.form = visitorsForm
/**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
export const membersList = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: membersList.url(options),
    method: 'get',
})

membersList.definition = {
    methods: ["get","head"],
    url: '/impression/liste-membres',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
membersList.url = (options?: RouteQueryOptions) => {
    return membersList.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
membersList.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: membersList.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
membersList.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: membersList.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
    const membersListForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: membersList.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
        membersListForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: membersList.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::membersList
 * @see app/Http/Controllers/PrintController.php:50
 * @route '/impression/liste-membres'
 */
        membersListForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: membersList.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    membersList.form = membersListForm
/**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
export const monthlyAttendances = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: monthlyAttendances.url(options),
    method: 'get',
})

monthlyAttendances.definition = {
    methods: ["get","head"],
    url: '/impression/presences-mois',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
monthlyAttendances.url = (options?: RouteQueryOptions) => {
    return monthlyAttendances.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
monthlyAttendances.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: monthlyAttendances.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
monthlyAttendances.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: monthlyAttendances.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
    const monthlyAttendancesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: monthlyAttendances.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
        monthlyAttendancesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: monthlyAttendances.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::monthlyAttendances
 * @see app/Http/Controllers/PrintController.php:66
 * @route '/impression/presences-mois'
 */
        monthlyAttendancesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: monthlyAttendances.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    monthlyAttendances.form = monthlyAttendancesForm
/**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
export const communionPrepared = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: communionPrepared.url(options),
    method: 'get',
})

communionPrepared.definition = {
    methods: ["get","head"],
    url: '/impression/communion-prepares',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
communionPrepared.url = (options?: RouteQueryOptions) => {
    return communionPrepared.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
communionPrepared.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: communionPrepared.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
communionPrepared.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: communionPrepared.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
    const communionPreparedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: communionPrepared.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
        communionPreparedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: communionPrepared.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PrintController::communionPrepared
 * @see app/Http/Controllers/PrintController.php:92
 * @route '/impression/communion-prepares'
 */
        communionPreparedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: communionPrepared.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    communionPrepared.form = communionPreparedForm
const print = {
    index: Object.assign(index, index),
members: Object.assign(members, members),
visitors: Object.assign(visitors, visitors),
membersList: Object.assign(membersList, membersList),
monthlyAttendances: Object.assign(monthlyAttendances, monthlyAttendances),
communionPrepared: Object.assign(communionPrepared, communionPrepared),
}

export default print